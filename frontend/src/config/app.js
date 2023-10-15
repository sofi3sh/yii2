import CONFIG from './environment';

const { API_BASE_URL } = CONFIG;
const APP_CONFIG = Object.freeze({
  API_BASE_URL,
  API_VERSION: 'api/v1',
  STATUSES: {
    IN_WORK: 'in_work'
  },
  FILE_ACCESS_ACTIONS: {
    view: 1,
    edit: 2
  },
  PRODUCTS: {
    INTERNAL_DRAINAGE: 'internal_drainage',
    BRIDGE_TRAY: 'bridge_tray'
  },
  PRODUCT_OPTION_FILE_TYPE_ID: 5,
  PRODUCT_OPTIONS: {
    PRODUCT_KEY: 'product_key',
    CLIENT_ID: 'client_id',
    ALLOW_FRAGMENTS: 'allow_fragments',
    HYDRAULIC_SPLIT: 'hydraulic_split',
    HYDRAULIC_TRAY_LENGTH: 'hydraulic_tray_length',
    HYDRAULIC_TRAY_SLOPE_CHECKBOX: 'hydraulic_tray_slope_checkbox',
    HYDRAULIC_TRAY_SLOPE: 'hydraulic_tray_slope',
    HYDRAULIC_CONNECTION_TYPE: 'hydraulic_connection_type',
    HYDRAULIC_DRAINAGE_TYPE: 'hydraulic_drainage_type',
    HYDRAULIC_RELEASE_DIRECTION: 'hydraulic_release_direction',
    HYDRAULIC_RELEASE_PLACEMENT: 'hydraulic_release_placement',
    HYDRAULIC_RELEASE_PLACEMENT_END: 'hydraulic_release_placement_end',
    HYDRAULIC_WATER_SEAL: 'hydraulic_water_seal',
    HYDRAULIC_WATER_SEAL_AND_CATCHER: 'hydraulic_water_seal_and_catcher',
    HYDRAULIC_GRILLE: 'hydraulic_grille',
    HYDRAULIC_GRILLE_TYPE: 'hydraulic_grille_type',
    HYDRAULIC_GRILLE_TYPE_PERFORATED: 'hydraulic_grille_type_perforated',
    HYDRAULIC_GRILLE_TYPE_NON_STANDARD: 'hydraulic_grille_type_non_standard',
    HYDRAULIC_EURO_100: 'euro_100',
    HYDRAULIC_HEIGHT_MIN: 'height_min',
    HYDRAULIC_HEIGHT_MIN_CHECKBOX: 'height_min_checkbox',
    HYDRAULIC_HEIGHT_MAX: 'height_max',
    HYDRAULIC_HEIGHT_MAX_CHECKBOX: 'height_max_checkbox',
    HYDRAULIC_FLANGE: 'hydraulic_flange',
    HYDRAULIC_UNDER_WELDING: 'hydraulic_under_welding',
    HYDRAULIC_TUBULAR_OUTPUT: 'hydraulic_tubular_output',
    HYDRAULIC_RELEASE_DIRECTION_LEFT: 'hydraulic_release_direction_left',
    BRIDGE_TRAY_FILE: 'bridge_tray_file',
    HYDRAULIC_EURO_HEIGHT_MIN: 'euro_height_min',
    HYDRAULIC_EURO_HEIGHT_MAX: 'euro_height_max',
    HYDRAULIC_GRILLE_NON_STANDARD_FILE: 'hydraulic_grille_non_standard_file',
    HYDRAULIC_GRILLE_ADJUSTMENT_CUSTOMER:
      'hydraulic_grille_adjustment_by_customer',
    HYDRAULIC_GRILLE_ADJUSTMENT_MANUFACTURE:
      'hydraulic_grille_adjustment_by_manufacturer',
    HYDRAULIC_GRILLE_TYPE_WITHOUT_GRILLE:
      'hydraulic_grille_type_without_grille',
    NO_END_LID_IN_BEGINNING_OF_LINE: 'no_end_lid_in_beginning_of_line',
    OUTFALL_DIAMETR: 'Outfall_diametr',
    OUTFALL_DIAMETR_100: 'DN100',
    HYDRAULIC_WITHOUT_OUTPUT: 'hydraulic_without_output',
    HYDRAULIC_SLIT: 'slit'
  },
  PRODUCT_OPTION_TYPES: {
    INPUT: 1,
    CHECKBOX: 2,
    DROPDOWN: 3,
    DROPDOWN_OPTION: 4,
    FILE: 5
  },
  IMAGES: {
    HYDRAULIC_FLANGE: 'hydraulic_flange.png',
    HYDRAULIC_UNDER_WELDING: 'hydraulic_under_welding.png',
    HYDRAULIC_SLIT: 'hydraulic_slit.png',
    HYDRAULIC_MINI: 'hydraulic_mini.png',
    HYDRAULIC_EURO: 'hydraulic_euro.png',
    HYDRAULIC_EURO_SP: 'hydraulic_euro_sp.png'
  },
  PRODUCT_IDS: {
    INTERNAL_DRAINAGE: '1'
  },
  STATE: {
    INVISIBLE: -1
  }
});

export default APP_CONFIG;
