
var ArtistAndListenerCreator =
{

    /*********************************************************************************
     * METHOD init.
     * Initialises, loads stripe if needed
     * binds and renders view based upon artist
     * or listener type
     *********************************************************************************/
    init: function(type)
    {
        if(window.Stripe === undefined)
        {
            this.loadStripe(type);
        }
        else
        {
            this.setStripeKey();
            this.cacheDOM(type);
            this.bindEvents(type);
            this.render(type);
        }
    },


    /*********************************************************************************
     * METHOD loadStripe.
     * Loads Stripe by ajax
     * then callsback to init()
     *********************************************************************************/
    loadStripe: function(type)
    {
        $.ajax({
            url: "https://js.stripe.com/v3/",
            dataType: "script",
            cache: true,
            success: (function()
            {
                this.init(type);
            }).bind(this)
        });
    },


    /*********************************************************************************
     * METHOD setStripeKey.
     * Sets the stripe key by ajax
     *********************************************************************************/
    setStripeKey: function()
    {
        this.stripe = window.Stripe('pk_test_QEqgqqJj66miXBORjz7XSSpV');
    },


    /*********************************************************************************
     * METHOD cacheDOM.
     * Grabs all DOM elements with jQuery
     * determines form based on type
     *********************************************************************************/
    cacheDOM: function(type)
    {
        var formName = '#' + type + '-form';
        this.$form = $(formName);
        this.$errorelement = $('#stripe_errors');
        this.$submit = $('#submit-button');
    },


    /*********************************************************************************
     * METHOD bindEvents.
     * Binds to submit
     *********************************************************************************/
    bindEvents: function(type)
    {
        this.$submit.on('click', this.createSubmissionToken.bind(this,type))
    },


    /*********************************************************************************
     * METHOD render.
     * Renders card input
     * if type is listener.
     * Does nothing
     * for artist
     *********************************************************************************/
    render: function(type)
    {
        if(type === 'listener')
        {
           this.renderCardElement()
        }
    },


    /*********************************************************************************
     * METHOD renderCardElement.
     * Renders the fancy stripe card input
     * and mounts into DOM
     *********************************************************************************/
    renderCardElement: function()
    {
        this.elements = this.stripe.elements();
        this.cardInputStyle =
        {
            base:
            {
                color: '#303238',
                fontSize: '16px',
                lineHeight: '48px',
                fontSmoothing: 'antialiased',
                '::placeholder':
                {
                    color: '#ccc',
                },
            },
            invalid:
            {
                color: '#e5424d',
                ':focus':
                {
                    color: '#303238',
                },
            },
        };

        this.cardElement = this.elements.create('card', {style: this.cardInputStyle});
        this.cardElement.mount('#stripe_input');  //SEE IF YOU CAN PASS THIS A JQUERY OBJECT
    },


    /*********************************************************************************
     * METHOD generateCardToken.
     * Generates the stripe card token
     *********************************************************************************/
    generateCardToken: function()
    {

           this.stripe.createToken(this.cardElement).then(
            (function(result)
                {
                    this.validateAndSubmit(result);
                }).bind(this)
        );


    },


    /*********************************************************************************
     * METHOD generateBankToken.
     * Generates the stripe bank account token
     *********************************************************************************/
    generateBankToken: function()
    {
        this.stripe.createToken('bank_account',
            {
                country: 'us',
                currency: 'usd',
                routing_number: '110000000',
                account_number: '000123456789',
                account_holder_name: $('#first_name').val() + ' ' + $('#last_name').val(),
                account_holder_type: 'individual'
            }).then(
                (function(result)
                    {
                        this.validateAndSubmit(result);
                    }).bind(this)
            );

    },


    /*********************************************************************************
     * METHOD formSubmit.
     * Bound to submit button
     * Selects relevant token to generate
     * based upon listener or artist
     *********************************************************************************/
    createSubmissionToken: function(type)
    {
        if(type === 'listener')
        {
          this.generateCardToken();
        }
        else if(type === 'artist')
        {
          this.generateBankToken();
        }
    },


    /*********************************************************************************
     * METHOD validateAndSubmit.
     * Callback from token methods
     * Determines if token is valid
     * Submits form or returns error
     *********************************************************************************/
    validateAndSubmit: function(result)
    {
        if (result.error)
        {
            this.errorElement.textContent = result.error.message;
        }
        else
        {
            var token = result.token.id;
            this.$form.append('<input id="stripe_token" name="stripe_token" type="hidden" value="'+ token + '">');
            this.$form.submit();
        }
    }


}

//var elements = stripe.elements();
//
//    var style = {
//        base: {
//            color: '#303238',
//            fontSize: '16px',
//            lineHeight: '48px',
//            fontSmoothing: 'antialiased',
//            '::placeholder': {
//                color: '#ccc',
//            },
//        },
//        invalid: {
//            color: '#e5424d',
//            ':focus': {
//                color: '#303238',
//            },
//        },
//    };

//    var cardElement = elements.create('card', {style: style});
//    cardElement.mount('#stripe_input');
//
//    var submit = $('#submit');
//    var form = $('#listener_form');
//
//    submit.click( function()
//    {
//        stripe.createToken(cardElement).then(function(result)
//        {
//            if (result.error)
//            {
//                var errorElement = $('#card_errors');
//                errorElement.textContent = result.error.message;
//            }
//            else
//            {
//                var tokenInput = $('#payment_token')
//                tokenInput.val(result.token.id);
//                form.submit();
//            }
//        });
//    });
//
//



