
var TrackUploader =
{

    /*********************************************************************************
     * METHOD init.
     * Runs when object is instantiated
     * and mounts into DOM
     *********************************************************************************/
    init: function(mode)
    {
        this.initialiseDropzone();
        this.cacheDOM();
        this.bindEvents();
        this.bindOneTimeEvents();

        if(mode === 'edit')
        {
            this.getUploadedTracks(this.releaseID);
        }

    },


    /*********************************************************************************
     * METHOD bindEvents.
     * Bind the events required
     *********************************************************************************/
    bindEvents: function()
    {
        this.bindClearTrackTitleTooltips();
        this.bindSortableFunctions();
        this.updateDeleteButtons();
        this.bindDeleteFunctions();
    },


    /*********************************************************************************
     * METHOD bindOneTimeEvents.
     * Bind the events required
     * only once by the page
     *********************************************************************************/
    bindOneTimeEvents: function()
    {
        this.$addRow.on('click', this.addTrackRow.bind(this));
        this.$finish.on('click', this.finishTrackUpload.bind(this));

        var url = window.location.pathname;
        this.releaseID = url.substring(url.lastIndexOf('/') + 1);
    },


    /*********************************************************************************
     * METHOD cacheDOM.
     * Get the DOM entities
     *********************************************************************************/
    cacheDOM: function()
    {
        this.$addRow = $('#add-a-row');
        this.$finish = $('#finish');
        this.$list = $('#trackList');

        this.trackTitlesArray = $('div[class*=track-title-group]');
        this.token = $( "input[name='_token']").val();
        this.trackRowsArray = $('div[class*=track-row]');
    },


    /*********************************************************************************
     * METHOD initialiseDropzone.
     * Grab all dropzone elements and
     * attach the dropzone object to each
     *********************************************************************************/
    initialiseDropzone: function()
    {

        //IMPORT THE DROPZONE LIBRARY FROM WINDOW INTO OBJECT
        if(!this.Dropzone)
        {
            this.Dropzone = window.Dropzone;
        }

        //FIND ALL TRACK DROPZONE ELEMENTS
        var dropzoneArray = $('[id^="track-dropzone"]');

        //CHECK WHETHER DROPZONE IS ALREADY INSTANTIATED
        if (typeof this.trackDropzones === "undefined")
        {
            //IF NOT, INSTANTIATE ARRAY
            this.trackDropzones = [];
        }

        //LOOP THROUGH ELEMENTS TO ATTACH DROPZONE
        for (var i = 0; i < dropzoneArray.length; i++)
        {
            //EXTRACT ID OF EACH ELEMENT
            var DropzoneElementID = '#' + $(dropzoneArray[i]).attr('id');

            //CHECK IF THIS ELEMENT ALREADY HAS AN ASSOCIATED DROPZONE.
            // IF SO, SKIP
            if($(dropzoneArray[i]).get(0).dropzone)
            {
                continue;
            }

            //GENERATE THE UPLOAD ID FOR THIS FILE
            this.generateUploadID(DropzoneElementID);

            //ATTACH DROPZONE
            this.trackDropzones[i] = new this.Dropzone(DropzoneElementID,
                {
                    addRemoveLinks: true,
                    maxFiles: 1,
                    acceptedFiles: ".mp3" //TODO CHANGE
                });

        }

        this.bindDropzoneAcceptanceEvents();
        this.bindDropzoneRemovalEvents();
        this.bindDropzoneUploadEvents();

    },


    /*********************************************************************************
     * METHOD bindDropzoneUploadEvents.
     * Attach the acceptance function
     * and callback to each dropzone object
     *********************************************************************************/
    bindDropzoneUploadEvents : function()
    {

        //LOOP THROUGH ARRAY OF TRACK DROPZONES AND ATTACH SENDING EVENTS
        var i;
        for (i = 0; i < this.trackDropzones.length; ++i)
        {

            this.trackDropzones[i].on('sending',
                ( function(i)
                {

                    return function(file, xhr, formData)
                    {
                        var trackDropzone = this.trackDropzones[i];

                        //GET ALL ASSOCIATED DATA FOR TRACK FILE
                        var trackTitle = $(trackDropzone.element).closest('.row').find('.track-title').val();
                        var tracknumber = $(trackDropzone.element).closest('.row').find('.track-number').val();
                        var uploadID = $(trackDropzone.element).closest('.row').find('.upload-id').val();
                        var token = this.token;

                        //APPEND THE ADDITIONAL DATA TO THE DROPZONE SUBMISSION
                        formData.append('track_title', trackTitle);
                        formData.append('track_number', tracknumber);
                        formData.append('upload_id', uploadID);
                        formData.append('_token', token);
                    }

                }(i)

                    ).bind(this)
            );
        }


    },


    /*********************************************************************************
     * METHOD bindDropzoneAcceptanceEvents.
     * Define the acceptance function
     * for each Dropzone element
     *********************************************************************************/
    bindDropzoneAcceptanceEvents : function()
    {

        for (var i = 0; i < this.trackDropzones.length; i++)
        {

            this.trackDropzones[i].options.accept =
                (function(i)
                {
                    return function(file, done)
                    {
                        var trackDropzone = this.trackDropzones[i];
                        var trackTitleElement = $(trackDropzone.element).closest('.row').find('.track-title');

                        //CHECK IF TRACK TITLE IS ENTERED FOR THIS INPUT
                        if (!trackTitleElement.val())
                        {
                            //ADD ERRORS AND WARNINGS
                            trackTitleElement.parent().removeClass().addClass('form-group has-error');
                            trackTitleElement.tooltip(
                                {
                                    'show': true,
                                    'placement': 'bottom',
                                    'title': "Please enter track title before uploading file..."
                                });

                            trackTitleElement.tooltip('show');

                            //REMOVE THE FILE
                            window.setTimeout(
                                (function()
                                {
                                    trackDropzone.removeFile(file);
                                }
                                    ).bind(this),2000
                            )
                        }
                        else
                        {
                            done();
                        }
                    }.bind(this)
                }.bind(this)
                    )(i)

        }
    },


    /*********************************************************************************
     * METHOD bindDropzoneRemovalEvents.
     * Define the function
     * to change upload id if file deleted
     *********************************************************************************/
    bindDropzoneRemovalEvents : function()
    {

        for (var i = 0; i < this.trackDropzones.length; i++)
        {
            this.trackDropzones[i].on('removedfile',
                (function(i)
                {
                    return function(file)
                    {
                        var DropzoneElementID = '#' + $(this.trackDropzones[i].element).attr('id');
                        this.generateUploadID(DropzoneElementID);
                    }

                }(i)
                    ).bind(this)
            );

        }
    },


    /*********************************************************************************
     * METHOD generateUploadID.
     * Create random string
     * to keep track of uploaded files
     *********************************************************************************/
    generateUploadID : function(DropzoneElementID)
    {
        //GET THE UPLOAD ID INPUT FIELD
        var DropzoneElement = $(DropzoneElementID);
        var uploadIDInput = DropzoneElement.closest('.row').find('.upload-id');

        //GENERATE RANDOM STRING
        var uploadID = (Math.random()*1e32).toString(36);

        //PUT STRING INTO INPUT FIELD
        $(uploadIDInput).val(uploadID);

    },


    /*********************************************************************************
     * METHOD bindSortableFunctions.
     * Create sortable.js function
     * on track list
     *********************************************************************************/
    bindSortableFunctions: function()
    {
        this.$list.sortable(
            {
                animation: 150,
                update: function (e , ui)
                {

                    var trackRow = ui.item;  // dragged HTMLElement
                    var newIndex = trackRow.index()+1;
                    trackRow.attr('id', "track-group-" + newIndex);

                    var trackNumber = trackRow.find('.track-number');
                    trackNumber.val(newIndex);

                    var dropzoneElement = trackRow.find('.dropzone');
                    dropzoneElement.attr('id','track-dropzone-' + newIndex);

                    this.cacheDOM();
                    this.reorderTracks();

                }.bind(this)
            }
        );
    },

    /*********************************************************************************
     * METHOD bindClearTrackTitleTooltips.
     * Clear any tooltips
     * on blur from title input
     *********************************************************************************/
    bindClearTrackTitleTooltips: function()
    {
        //LOOP THROUGH THE ARRAY OF TRACK TITLES
        var i;
        for (i = 0; i < this.trackTitlesArray.length; i++)
        {
            //FOR EACH TRACK INPUT
            var trackTitleElement = $(this.trackTitlesArray[i]);
            var trackTitleInput = trackTitleElement.find('.track-title');

            //ON LOSS OF FOCUS FROM TRACK INPUT
            trackTitleInput.blur(function()
            {
                //IF THERE IS A VALID TRACK TITLE
                if(trackTitleInput.val().length > 0)
                {
                    //CLEAR ERRORS AND TOOLTIPS
                    trackTitleInput.tooltip('destroy');
                    trackTitleElement.removeClass().addClass('form-group track-title-group');

                }
            });
        }

    },




    /*********************************************************************************
     * METHOD updateDeleteButtons.
     * Dynamcally update delete buttons
     * for each track row depending on number of rows
     *********************************************************************************/
    updateDeleteButtons : function()
    {

        //LOOP THROUGH TRACK ARRAY
        for (var i=0; i < this.trackRowsArray.length; i++)
        {
            var trackRow = this.trackRowsArray[i];
            //FIND DELETE BUTTON CONTAINER
            var deleteButtonDiv = $(trackRow).find('.delete-button-div');
            //CLEAR CONTENTS
            deleteButtonDiv.empty();

            //IF THERE IS ONLY ONE TRACK, DO NOT ADD A DELETE BUTTON
            if (this.trackRowsArray.length === 1)
            {
                return;
            }
            else
            {
                //IF NOT, CREATE DELETE BUTTON
                var deleteButton = $('<span>', {
                    'class': 'delete-button glyphicon glyphicon-remove'
                });

                //APPEND TO DELETE CONTAINER
                deleteButtonDiv.append(deleteButton);
            }

        }

    },


    /*********************************************************************************
     * METHOD bindDeleteFunctions.
     * Bind delete functionality
     * to each delete button
     *********************************************************************************/
    bindDeleteFunctions : function()
    {
        //COLLECT DELETE BUTTON CONTAINERS
        var deleteButtons = $('div[class*=delete-button]');
        var obj = this;

        //FOR EACH BUTTON
        deleteButtons.each(function()
        {

            if (!$(this).has('span').length)
            {
                //IF CONTAINER DOES NOT HAVE A BUTTON
                //IT IS A SINGLE TRACK, SO DO NOT BIND FUNCTION
                return true;
            }
            else
            {
                //GET THE BUTTON SPAN ELEMENT
                var clickElement = $(this).find('span');
                //BIND THE FUNCTION TO THE BUTTON
                clickElement.on('click', obj.deleteTrackRow.bind(this, obj));
            }

        });

    },


    /*********************************************************************************
     * METHOD addTrackRow.
     * Create a new row for
     * track upload
     *********************************************************************************/
    addTrackRow: function()
    {

        //COUNT THE NUMBER OF ROWS
        var trackRowsArray = this.trackRowsArray;
        var numberOfRows = trackRowsArray.length;

        //GRAB THE LAST ROW AND CLONE IT
        var lastRow = $(trackRowsArray[numberOfRows-1]);
        var newRow = lastRow.clone();

        //CLEAR ANY TOOLTIPS ASSOCIATED TO CLONED ROW
        var tooltip = newRow.find('.tooltip');
        $(tooltip).remove();

        //INCREMENT THE NUMBER
        numberOfRows++;

        //MODIFY THE CLONED ELEMENT
        newRow.attr('id', 'track-group-' + numberOfRows);
        newRow.find('.track-number').val(numberOfRows);
        newRow.find('.track-title').val('');

        var dropzoneElement = newRow.find('.dropzone');
        dropzoneElement.empty();
        dropzoneElement.attr('id', 'track-dropzone-' + numberOfRows);
        dropzoneElement.attr('class', 'dropzone');

        //APPEND THE CLONED ELEMENT AFTER LAST ROW
        lastRow.after(newRow);

        //REFRESH OBJECT
        this.cacheDOM();
        this.initialiseDropzone();
        this.bindEvents();

    },

    /*********************************************************************************
     * METHOD deleteTrackRow.
     * Delete a row
     * ************
     *********************************************************************************/
    deleteTrackRow : function(obj)
    {

        //GET THE DROPZONE ID OF THE DELETED TRACK ROW
        var dropzoneElementID = $(this).parent().find('.dropzone').attr('id');

        //LOOP THROUGH DROPZONE ARRAY TO FIND DELETED TRACK ROW ELEMENT
        for (var i = 0; i < obj.trackDropzones.length; i++)
        {
            //ID OF CURRENT ROW IN DROPZONE ARRAY
            var dropzoneArrayElementID = $(obj.trackDropzones[i].element).attr('id');

            //IF THE CURRENT ID MATCHES THE DELETED ID
            if(dropzoneElementID === dropzoneArrayElementID)
            {
                //DELETE THAT DROPZONE INSTANCE FROM DROPZONE ARRAY
                obj.trackDropzones.splice(i, 1);
            }
            else
            {
                continue;
            }
        }

        //REMOVE THE TRACK ROW
        $(this).closest('.track-row').remove();

        //REFRESH THE OBJECT
        obj.reorderTracks();
    },



    /*********************************************************************************
     * METHOD reorderTracks.
     * Renumber tracks in
     * the track list
     *********************************************************************************/
    reorderTracks : function()
    {

        //FIND ALL TRACK ROWS
        this.$list.find('.track-row').each(function(index)
        {
            //ACCOUNT FOR STARTING AT 1, NOT 0
            var trackNumber = index + 1;

            //GET CURRENT TRACK ROW
            var trackRow = $(this);

            //COMPARE EXPECTED ID BASED ON PLACE IN LIST
            var expectedID = "track-group-" + trackNumber;
            var observedID = trackRow.attr('id');
            if(observedID !== expectedID)
            {
                //IF THEY ARE NOT THE SAME, THE ID NEEDS CHANGING
                trackRow.attr('id', expectedID);

                //ALSO CHANGE THE TRACK NUMBER AND DROPZONE INSTANCE NUMBER
                trackRow.find('.track-number').val(trackNumber);
                trackRow.find('.dropzone').attr('id', 'track-dropzone-' + trackNumber);
            }

        });

        //REFRESH OBJECT
        this.cacheDOM();
        this.initialiseDropzone();
        this.bindEvents();

    },



    /*********************************************************************************
     * METHOD finishTrackUpload.
     * Loop through all files and submit
     * as unified form for validation
     *********************************************************************************/
    finishTrackUpload : function()
    {
        //GET THE RELEASE ID FROM THE URL
        var urlParams = window.location.pathname.split( '/' );
        var releaseID = urlParams[urlParams.length-1];

        //INSTANTIATE FORM FOR ALL TRACK DATA
        var tracksForm = $('<form>',
            {
                'action': 'http://' + window.location.host + '/tracks/finish/' + releaseID,
                'method': 'POST'
            });

        //LOOP THROUGH THE TRACK ROWS TO GET ALL TRACK DATA
        var i;
        for (i = 0; i < this.trackRowsArray.length; i++)
        {
            //COLLECT TRACK TITLE DATA
            var trackRow = $(this.trackRowsArray[i]);
            var trackTitleInput = trackRow.find('.track-title');
            var trackTitle = trackTitleInput.val();

            //CHECK IF THERE IS NO TRACK TITLE
            if(trackTitle == "")
            {
                //ADD ERROR WARNINGS AND EXIT
                trackTitleInput.parent().removeClass().addClass('form-group track-title-group has-error');
                trackTitleInput.tooltip(
                    {
                        'show': true,
                        'placement': 'bottom',
                        'title': "Please enter track title..."
                    });
                trackTitleInput.tooltip('show');

                return false;
            }

            //CHECK IF THERE ARE NO FILES ATTACHED TO ANY TRACKS
            if (!this.trackDropzones[i].files || !this.trackDropzones[i].files.length)
            {

                //ADD ERROR WARNINGS AND EXIT
                $(this.trackDropzones[i].element).tooltip(
                    {
                        'show': true,
                        'placement': 'bottom',
                        'title': "Please provide a file..."
                    });
                $(this.trackDropzones[i].element).tooltip('show');

                return false;
            }

            //COLLECT TRACK NUMBER DATA
            var trackNumber = trackRow.find('.track-number').val();

            //COLLECT UPLOAD ID
            var uploadID = trackRow.find('.upload-id').val();

            //COLLECT THE TOKEN
            var token = this.token;

            //COMPILE DATA INTO SINGLE OBJECT
            var trackDataObject =
            {
                trackNumber: trackNumber,
                trackTitle: trackTitle,
                uploadID: uploadID
            };

            //APPEND DATA TO FORM
            tracksForm.append($('<input>',
                {
                    'name': i,
                    'value': JSON.stringify(trackDataObject),
                    'type': 'hidden'
                })
            );

        }

        //APPEND TOKEN TO FORM
        tracksForm.append($('<input>',
            {
                'name': '_token',
                'value': token,
                'type': 'hidden'
            }));


        //APPEND FORM TO DOCUMENT TO AVOID SECURITY ERRORS
        $(document.body).append(tracksForm);

        //SUBMIT
        tracksForm.submit();

    },


    getUploadedTracks : function(releaseID)
    {


        $.ajax(
            {
                method: "GET",
                url: "/tracks/show/" + releaseID,
            }
        )
            .done(function( response ) {
                console.log( response );

                for (var i = 0; i < response.length; i++)
                {
                    var filename = response[i].original_filename;
                    //TODO get filesize from DB
                    var trackNumber = response[i].track_number;

                    var dropzoneClass = '#track-dropzone-' + track_number;

                    console.log($(dropzoneClass));

                }

            }.bind(this));

        //talk to db
        //get original filenames and sizez of all tracks
        //manually create representations in the dropzone elements
        //create handlers on the upload id to represent an unchanged file when the finish event is triggered
    }



};



