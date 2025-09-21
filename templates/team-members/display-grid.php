<?php

/**
 * Team Members Grid Display Template
 */

$team_members = get_field('team_members');

if (! $team_members) {
  return;
}

$columns = $args['columns'] ?? 3;
$col_class = 'col-md-' . (12 / $columns);
?>

<div class="acfdt-team-members-grid">
  <div class="<?php echo get_option('acfdt_use_bootstrap') ? 'container' : 'acfdt-container'; ?>">
    <div class="row">
      <?php foreach ($team_members as $member) : ?>
        <div class="<?php echo esc_attr($col_class); ?> mb-4">
          <div class="acfdt-team-member-card h-100">
            <?php if ($member['photo']) : ?>
              <div class="acfdt-member-photo">
                <img src="<?php echo esc_url($member['photo']['sizes']['medium']); ?>"
                  alt="<?php echo esc_attr($member['name']); ?>"
                  class="img-fluid rounded-circle">
              </div>
            <?php endif; ?>

            <div class="acfdt-member-info text-center p-3">
              <h3 class="h5"><?php echo esc_html($member['name']); ?></h3>

              <?php if ($member['position']) : ?>
                <p class="text-muted"><?php echo esc_html($member['position']); ?></p>
              <?php endif; ?>

              <?php if ($member['bio']) : ?>
                <p class="small"><?php echo esc_html($member['bio']); ?></p>
              <?php endif; ?>

              <div class="acfdt-member-links">
                <?php if ($member['email']) : ?>
                  <a href="mailto:<?php echo esc_attr($member['email']); ?>"
                    class="btn btn-sm btn-outline-primary me-2">
                    Email
                  </a>
                <?php endif; ?>

                <?php if ($member['linkedin']) : ?>
                  <a href="<?php echo esc_url($member['linkedin']); ?>"
                    target="_blank"
                    class="btn btn-sm btn-outline-info">
                    LinkedIn
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>