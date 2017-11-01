<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Config;

use Pbmedia\LaravelFFMpeg\FFMpegFacade;
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Facades\Storage;
use App\Track;

class ConvertTrack extends Job implements SelfHandling, ShouldQueue
{

    use InteractsWithQueue, SerializesModels;

    protected $trackFullLocation;
    protected $trackFilePath;
    protected $trackNumber;
    protected $track_id;

    public function __construct($trackFullLocation, $trackFilePath, $trackNumber, $track_id)
    {
        $this->trackFullLocation = $trackFullLocation;
        $this->trackFilePath = $trackFilePath;
        $this->trackNumber = $trackNumber;
        $this->track_id = $track_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo 'Conversion Task Received';

        $VBRConfig = Config::get('bitrates');

        $highBitrate = (new X264('aac', 'vn'))->setAudioKiloBitrate($VBRConfig['highRate']);
        $medBitrate = (new X264('aac', 'vn'))->setAudioKiloBitrate($VBRConfig['medRate']);
        $lowBitrate = (new X264('aac', 'vn'))->setAudioKiloBitrate($VBRConfig['lowRate']);

        FFMpegFacade::fromDisk('s3')
            ->open($this->trackFullLocation)
            ->export()
            ->toDisk('s3')
            ->inFormat($highBitrate)
            ->save($this->trackFilePath . '/' . $this->trackNumber . '_high.mp4')

            ->export()
            ->toDisk('s3')
            ->inFormat($medBitrate)
            ->save($this->trackFilePath . '/' . $this->trackNumber . '_med.mp4')

            ->export()
            ->toDisk('s3')
            ->inFormat($lowBitrate)
            ->save($this->trackFilePath . '/' . $this->trackNumber . '_low.mp4');

        $SMIL_data =
            [
                'trackFilePath' => $this->trackFilePath,
                'trackNumber'   => $this->trackNumber,
                'VBRConfig'     => $VBRConfig,

            ];

        $SMIL_filename = $this->trackNumber . '.smil';
        $SMIL_content =  view('partials.smil')->with(['SMIL_data' => $SMIL_data])->render();

        $disk = Storage::disk('s3');
        $disk->put($this->trackFilePath . '/' . $SMIL_filename, $SMIL_content);

        FFMpegFacade::cleanupTemporaryFiles();

        $track = Track::findOrFail($this->track_id);
        $track->markConversionComplete();
        $track->save();


    }
}
