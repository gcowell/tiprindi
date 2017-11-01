<smil>
    <head></head>
    <body>
    <switch>
        <audio src="mp4:{{$SMIL_data['trackNumber']}}_low.mp4">
            <param name="audioBitrate" value="{{$SMIL_data['VBRConfig']['lowRate'] . '000'}}" valuetype="data"></param>
            <param name="audioCodecId" value="mp4a.40.2" valuetype="data"/>
        </audio>
        <audio src="mp4:{{$SMIL_data['trackNumber']}}_med.mp4">
            <param name="audioBitrate" value="{{$SMIL_data['VBRConfig']['medRate'] . '000'}}" valuetype="data"></param>
            <param name="audioCodecId" value="mp4a.40.2" valuetype="data"/>
        </audio>
        <audio src="mp4:{{$SMIL_data['trackNumber']}}_high.mp4">
            <param name="audioBitrate" value="{{$SMIL_data['VBRConfig']['highRate'] . '000'}}" valuetype="data"></param>
            <param name="audioCodecId" value="mp4a.40.2" valuetype="data"/>
        </audio>
    </switch>
    </body>
</smil>