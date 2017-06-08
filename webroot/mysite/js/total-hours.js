jQuery.noConflict();


/**
 * This is used to sum up hours calculations on the fly
 */
(function($) {

        function HoursListener(){
            this.debug('Initializing');
            this.init();
        };

        HoursListener.prototype = {
            init: function() {
                this.debugFlag = true;
                this.debug('Listening for form');
                this.listenForForm();
                this.checkPageForForm();
                this.listenForNewLineItems();
            },

            debug: function(msg){
                if(this.debugFlag){
                    console.log(msg);
                }
            },

            /**
             * Check to see if stories gridfield is on page
             * @returns {*|HTMLElement}
             */
            checkPageForForm: function(){
                var form = $('#Form_ItemEditForm');
                return form && this.checkStoriesForm(form[0]);

            },

            checkStoriesForm: function(form){
                return form.action.search('Stories') > 0
            },

            isStoriesForm: function(obj){
                if(obj && 'element' in obj && 1 in obj.element){
                    var form = obj['element'][1];
                    return this.checkStoriesForm(form);
                }
            },

            listenForNewLineItems: function(){
                $(document).on('addnewinline', function(){
                    this.debug('New text field addded');
                    this.listenForHoursChanges();
                }.bind(this));
            },

            /**
             * Listen for ajax'd in stories gridfield
             */
            listenForForm: function(){
                $(document).on('afterstatechange', function(evt, obj){
                    if(this.isStoriesForm(obj)){
                        this.debug('Stories form is here');
                        this.listenForHoursChanges();
                        this.listenForNewLineItems();
                    } else {
                        this.debug('No stories form present');
                    }
                }.bind(this));


                /**
                 * Could exists on page already
                 */
                if(this.checkPageForForm()){
                    this.debug('Stories form is here');
                    this.listenForHoursChanges();
                    this.listenForNewLineItems();
                }
            },

            listenForHoursChanges: function(){
                var hoursRef = this;
                $('.col-NumHours input[type="text"]').keyup(function(){
                    hoursRef.debug('Listening for hours changes then setting');
                    hoursRef.setTotalHours(hoursRef.getTotalHours());
                });
            },

            getTotalHours: function(){
                //RIP Sam
                return $('.col-NumHours input[type="text"]').toArray().reduce(
                    function(sum, element){
                        return sum + Number(element.value);
                    }, 0);
            },

            setTotalHours: function(totalHours){
                $('#Form_ItemEditForm_Total_Hours').text(totalHours);
            }
        }

    $(document).ready(function(){
        var hoursListener = new HoursListener();
    })
}(jQuery));