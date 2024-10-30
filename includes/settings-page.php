<?php
/**
* custom option and settings
*/
function cfi_settings_init() {
  // register a new setting for "cfi" page
  register_setting( 'cfi', 'cfi_options' );

  // register a new section in the "cfi" page
  add_settings_section(
    'cfi_section_developers',
    __( 'Settings page.', 'cfi' ),
    'cfi_section_developers_cb',
    'cfi'
  );

  add_settings_field(
    'cfi_field_link',
    'Link',
    'cfi_field_link_cb',
    'cfi',
    'cfi_section_developers',
    [
      'label_for' => 'cfi_field_link',
      'class' => 'cfi_row',
    ]
  );

  add_settings_field(
    'cfi_field_graph',
    'Graph',
    'cfi_field_graph_cb',
    'cfi',
    'cfi_section_developers',
    [
      'label_for' => 'cfi_field_graph',
      'class' => 'cfi_row',
    ]
  );

  add_settings_field(
    'cfi_field_volume',
    'Volume',
    'cfi_field_volume_cb',
    'cfi',
    'cfi_section_developers',
    [
      'label_for' => 'cfi_field_volume',
      'class' => 'cfi_row',
    ]
  );

  add_settings_field(
    'cfi_field_market_cap',
    'Market Cap',
    'cfi_field_market_cap_cb',
    'cfi',
    'cfi_section_developers',
    [
      'label_for' => 'cfi_field_market_cap',
      'class' => 'cfi_row',
    ]
  );
}

/**
* register our cfi_settings_init to the admin_init action hook
*/
add_action( 'admin_init', 'cfi_settings_init' );

/**
* custom option and settings:
* callback functions
*/
function cfi_section_developers_cb( $args ) {
  ?>
  <p id="<?php echo esc_attr( $args['id'] ); ?>">View settings.</p>
  <?php
}

function cfi_field_link_cb( $args ) {
  $options = get_option( 'cfi_options' );
  // output the field
  ?>
  <label for="<?php echo esc_attr( $args['label_for'] ); ?>">Hide link:</label>
  <input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>"
    name="cfi_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
    <?php if($options[ $args['label_for'] ] === 'on') echo 'checked="checked"'; ?>
  >
  <?php
}

function cfi_field_graph_cb( $args ) {
  $options = get_option( 'cfi_options' );
  // output the field
  ?>
  <label for="<?php echo esc_attr( $args['label_for'] ); ?>">Show Graph:</label>
  <input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>"
    name="cfi_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
    <?php if($options[ $args['label_for'] ] === 'on') echo 'checked="checked"'; ?>
  >
  <?php
}

function cfi_field_volume_cb( $args ) {
  $options = get_option( 'cfi_options' );
  // output the field
  ?>
  <label for="<?php echo esc_attr( $args['label_for'] ); ?>">Show Daily Volume:</label>
  <input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>"
    name="cfi_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
    <?php if($options[ $args['label_for'] ] === 'on') echo 'checked="checked"'; ?>
  >
  <?php
}

function cfi_field_market_cap_cb( $args ) {
  $options = get_option( 'cfi_options' );
  // output the field
  ?>
  <label for="<?php echo esc_attr( $args['label_for'] ); ?>">Show Market Cap:</label>
  <input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>"
    name="cfi_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
    <?php if($options[ $args['label_for'] ] === 'on') echo 'checked="checked"'; ?>
  >
  <?php
}

/**
* top level menu
*/
function cfi_options_page() {
  // add top level menu page
  add_menu_page(
    'Cryptofavorite settings page.',
    'Cryptofavorite',
    'manage_options',
    'cfi',
    'cfi_options_page_html'
  );
}

/**
* register our cfi_options_page to the admin_menu action hook
*/
add_action( 'admin_menu', 'cfi_options_page' );

/**
* top level menu:
* callback functions
*/
function cfi_options_page_html() {
  // check user capabilities
  if ( ! current_user_can( 'manage_options' ) ) {
    return;
  }

  // add error/update messages

  // check if the user have submitted the settings
  // wordpress will add the "settings-updated" $_GET parameter to the url
  if ( isset( $_GET['settings-updated'] ) ) {
    // add settings saved message with the class of "updated"
    add_settings_error( 'cfi_messages', 'cfi_message', __( 'Settings Saved', 'cfi' ), 'updated' );
  }

  // show error/update messages
  settings_errors( 'cfi_messages' );
  ?>
  <div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form action="options.php" method="post">
      <?php
      // output security fields for the registered setting "cfi"
      settings_fields( 'cfi' );
      // output setting sections and their fields
      // (sections are registered for "cfi", each field is registered to a specific section)
      do_settings_sections( 'cfi' );
      // output save settings button
      submit_button( 'Save Settings' );
      ?>
    </form>
  </div>
  Example Shortcodes:
  <p><strong>[cryptofavorite-ticker coin="bitcoin" style="widget"]</strong></p>
  <p><strong>[cryptofavorite-ticker coin="eth"]</strong></p>
  <?php
}
