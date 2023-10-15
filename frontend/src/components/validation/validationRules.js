import CONFIG from '../../config/app';

const productOptionKeys = CONFIG.PRODUCT_OPTIONS;
const internalDrainageKey = CONFIG.PRODUCTS.INTERNAL_DRAINAGE;

export const number = value =>
  value && isNaN(Number(value))
    ? 'This field must contain a numeric value'
    : undefined;

export const required = value => (value ? undefined : 'This field is required');

export const valueBetweenRange = (value, allValues, props) => {
  const {
    [productOptionKeys.HYDRAULIC_EURO_HEIGHT_MIN]: heightMin,
    [productOptionKeys.HYDRAULIC_EURO_HEIGHT_MAX]: heightMax
  } = allValues;
  const {
    convertLengthToUserMeasurementUnit,
    orderData: {
      user: {
        measurementSystem: { defaultLengthUnit }
      }
    }
  } = props;

  const heightMaxConverted = convertLengthToUserMeasurementUnit(
    heightMax,
    defaultLengthUnit
  );
  const heightMinConverted = convertLengthToUserMeasurementUnit(
    heightMin,
    defaultLengthUnit
  );
  return heightMinConverted > +value || +value > heightMaxConverted
    ? `This field must be between {${heightMinConverted}} and {${heightMaxConverted}} values`
    : undefined;
};

export const numberMultiplicity05 = value =>
  value % 0.5 !== 0 ? 'This field must be a multiple of 0.5' : undefined;

export const waterSealCheckboxes = (value, allValues, props) => {
  const {
    [productOptionKeys.HYDRAULIC_WATER_SEAL]: waterSealCheckbox,
    [productOptionKeys.HYDRAULIC_WATER_SEAL_AND_CATCHER]: waterSealAndCatcherCheckbox
  } = allValues;
  const {
    orderData: {
      products: {
        [internalDrainageKey]: {
          parentOptions: {
            [productOptionKeys.HYDRAULIC_WATER_SEAL]: waterSeal,
            [productOptionKeys.HYDRAULIC_WATER_SEAL_AND_CATCHER]: waterSealAndCatcher
          }
        }
      }
    },
    getTranslation
  } = props;

  return (waterSealCheckbox && waterSealAndCatcherCheckbox) ||
    !(waterSealAndCatcherCheckbox || waterSealCheckbox)
    ? `You should select either the {${getTranslation(
      waterSeal
    )}} field or the {${getTranslation(
      waterSealAndCatcher
    )}} field but not both fields at the same time`
    : undefined;
};

export const traySlopeAndTrayHeight = (value, allValues) => {
  const {
    [productOptionKeys.HYDRAULIC_TRAY_SLOPE_CHECKBOX]: traySlopeCheckbox,
    [productOptionKeys.HYDRAULIC_HEIGHT_MIN_CHECKBOX]: heightMinCheckbox,
    [productOptionKeys.HYDRAULIC_HEIGHT_MAX_CHECKBOX]: heightMaxCheckbox
  } = allValues;

  return traySlopeCheckbox && heightMinCheckbox && heightMaxCheckbox
    ? 'You can select only two checkboxes out of three at a time'
    : undefined;
};

export const grilleAdjustmentCheckboxes = (value, allValues, props) => {
  const {
    [productOptionKeys.HYDRAULIC_GRILLE_ADJUSTMENT_CUSTOMER]: adjustmentByCustomerValue,
    [productOptionKeys.HYDRAULIC_GRILLE_ADJUSTMENT_MANUFACTURE]: adjustmentByManufactureValue
  } = allValues;
  const {
    orderData: {
      products: {
        [internalDrainageKey]: {
          parentOptions: {
            [productOptionKeys.HYDRAULIC_GRILLE_ADJUSTMENT_CUSTOMER]: adjustmentByCustomer,
            [productOptionKeys.HYDRAULIC_GRILLE_ADJUSTMENT_MANUFACTURE]: adjustmentByManufacture
          }
        }
      }
    },
    getTranslation
  } = props;

  return (adjustmentByCustomerValue && adjustmentByManufactureValue) ||
    (!adjustmentByCustomerValue && !adjustmentByManufactureValue)
    ? `You must select either the {${getTranslation(
      adjustmentByCustomer
    )}} field or the {${getTranslation(
      adjustmentByManufacture
    )}} field but not both fields at the same time`
    : undefined;
};