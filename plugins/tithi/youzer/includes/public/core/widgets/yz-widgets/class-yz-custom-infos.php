<?php

class YZ_Custom_Infos {

    /**
     * # Custom Informations.
     */
    function widget() {

        do_action( 'bp_before_profile_field_content' ); ?>

        <div class="yz-infos-content">

            <?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

                <?php if ( bp_field_has_data() ) : ?>

                    <div <?php bp_field_css_class( 'yz-info-item' ); ?>>

                        <div class="yz-info-label"><?php bp_the_profile_field_name(); ?></div>
                        <div class="yz-info-data"><?php bp_the_profile_field_value(); ?></div>

                    </div>

                <?php endif; ?>

                <?php do_action( 'bp_profile_field_item' ); ?>

            <?php endwhile; ?>

        </div>

        <?php

        do_action( 'bp_after_profile_field_content' ); 

    }

}