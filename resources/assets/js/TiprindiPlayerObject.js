var TiprindiPlayer =
{
    init: function()
    {
        if(window.WowzaPlayer === undefined)
        {
            this.loadWowza();
        }
        else
        {
            this.createPlayer();
            this.cacheDOM();
            this.bindEvents();
        }

    },
    loadWowza: function()
    {
        $.ajax({
            url: "//player.wowza.com/player/latest/wowzaplayer.min.js",
            dataType: "script",
            cache: true,
            success: (function()
            {
                this.init();
            }).bind(this)
        });
    },
    cacheDOM: function()
    {
        this.PlayerElement = window.WowzaPlayer.get('playerElement');
        this.$play = $("#play");
        this.$prev = $("#prev");
        this.$next = $("#next");

    },
    createPlayer: function(){

        window.WowzaPlayer.create('playerElement',
            {
                "license":"PLAY1-9Dvfb-9mw43-4rhdE-wK4nn-NWAhf",
                "title":"",
                "description":"",
                "sourceURL":"http://52.209.133.230:1935/Test1/_definst_/amazons3/1/1/smil:88.smil/playlist.m3u8",
                "autoPlay":false,
                "volume":"75",
                "mute":false,
                "loop":false,
                "audioOnly":true,
                "uiShowQuickRewind":false
            }
        );

    },

    createURL: function()
    {
        //    "http://52.209.133.230:1935/Test1/_definst_/amazons3/m4a:1/1/test.m4a/playlist.m3u8"
        //    "http%3A%2F%2F52.209.133.230%3A1935%2FTest1%2F_definst_%2Famazons3%2F5%2Fexample.mp4%2Fplaylist.m3u8"

    },
    bindEvents: function()
    {
        this.$play.on('click', this.playTrack.bind(this))
        this.$prev.on('click', this.prevTrack.bind(this))
        this.$next.on('click', this.nextTrack.bind(this))
    },

    playTrack: function()
    {
        this.PlayerElement.play();

    },

    nextTrack: function(){
        this.PlayerElement.stop();
        this.PlayerElement.setConfig(
            {
                "sourceURL":"http://52.209.133.230:1935/Test1/_definst_/amazons3/1/1/smil:wank.smil/playlist.m3u8"
            }
        )
        this.PlayerElement.play();
    },

    //HAVE A GENERIC CHANGE TRACK FUNCTION AND THEN HAVE GET NEXT AND GET PREV TRACKS _ CONSTRUCT AND PASS URL
    prevTrack: function(){
        this.PlayerElement.stop();
        this.PlayerElement.setConfig(
            {
                "sourceURL":"http://52.209.133.230:1935/Test1/_definst_/amazons3/1/1/smil:wank.smil/playlist.m3u8"
            }
        )
        this.PlayerElement.play();
    }
};



