( function( $ ) {

    'use strict';

    $( document ).ready( function() {

        $( document ).on( 'click', '#yz-skill-button' , function( e ) {

            var current_wg_nbr = $( '.yz-wg-item[data-wg=skills]' ).length + 1;

            if ( current_wg_nbr > yz_maximum_skills  )  {
				// Show Error Message
                $.ShowPanelMessage( {
                    msg  : yz.items_nbr + yz_maximum_skills,
                    type : 'error'
                } );
                return false;
            }

            e.preventDefault();

            var skills_title = $.ukai_form_input( {
                    input_desc      : yz.skill_desc_title,
                    cell            : yz_skill_nextCell,
                    option_item     : 'title',
                    options_name    : 'youzer_skills',
                    label_title     : yz.bar_title,
                    input_type      : 'text',
                    inner_option    : true
                }),

                skills_color = $.ukai_form_input( {
                    option_item     : 'barcolor',
                    input_desc      : yz.skill_desc_color,
                    cell            : yz_skill_nextCell,
                    options_name    : 'youzer_skills',
                    label_title     : yz.bar_color,
                    input_type      : 'color',
                    inner_option    : true
                }),

                skills_percent = $.ukai_form_input( {
                    option_item     : 'barpercent',
                    input_desc      : yz.skill_desc_percent,
                    cell            : yz_skill_nextCell,
                    options_name    : 'youzer_skills',
                    label_title     : yz.bar_percent,
                    input_type      : 'number',
                    input_min       : '1',
                    input_max       : '100',
                    inner_option    : true
                });

            // Add Skill
            $( '<li class="yz-wg-item" data-wg="skills">'+
                skills_title + skills_percent + skills_color
                + '<a class="yz-delete-item"></a></li>'
            ).hide().prependTo( '.yz-wg-skills-options' ).fadeIn( 400 );

            // increase ID number.
            yz_skill_nextCell++;

            // CallBack ColorPicker
            $( '.yz-picker-input' ).wpColorPicker();

            // Check Account Items List
            $.yz_CheckItemsList();

        });

        // ColorPicker
        $( '.yz-picker-input' ).wpColorPicker();

    });

})( jQuery );