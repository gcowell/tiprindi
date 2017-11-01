$(document).ready(function()
{
    var currentPage = ($("body").data("title")).replace(/^[ ]+|[ ]+$/g,'');

    //PLAYER INITIALISATION
    if(currentPage === 'player')
    {
        TiprindiPlayer.init();
    }

    //LISTENER INITIALISATION
    if(currentPage === 'listener-create')
    {
        ArtistAndListenerCreator.init('listener');
    }

    //ARTIST INITIALISATION
    if(currentPage === 'artist-create')
    {
        ArtistAndListenerCreator.init('artist');
    }

    //UPLOADER INITIALISATION
    if(currentPage === 'track-uploader')
    {
        TrackUploader.init('upload');
    }

    //UPLOADER INITIALISATION
    if(currentPage === 'track-editor')
    {
        TrackUploader.init('edit');
    }


});
