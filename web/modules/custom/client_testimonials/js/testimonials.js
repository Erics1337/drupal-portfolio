/**
 * @file
 * JavaScript behaviors for the client testimonials module.
 */

(function ($, Drupal) {
  'use strict';

  /**
   * Behavior for testimonial blocks.
   */
  Drupal.behaviors.clientTestimonials = {
    attach: function (context, settings) {
      // Simple rotation for testimonials if there are multiple blocks.
      $('.testimonials-block__items', context).once('testimonials-rotation').each(function () {
        var $items = $(this).find('.testimonials-block__item');

        // Only apply rotation if there are multiple items.
        if ($items.length > 1) {
          var currentIndex = 0;

          // Initially hide all but the first item.
          $items.hide().eq(0).show();

          // Set interval to rotate testimonials.
          setInterval(function () {
            $items.eq(currentIndex).fadeOut(500, function () {
              currentIndex = (currentIndex + 1) % $items.length;
              $items.eq(currentIndex).fadeIn(500);
            });
          }, 8000); // Rotate every 8 seconds.
        }
      });
    }
  };

})(jQuery, Drupal);
