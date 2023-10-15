import React from 'react';

import Dropdown from './Dropdown';
import ProductImage from '../ProductImage';
import { convertLengthToUserMeasurementUnit } from '../../containers/OrderFormContainer';
import withData from '../../hocs/withData';
import CONFIG from '../../config/app';

const hydraulicSplitKey = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_SPLIT;
const internalDrainageKey = CONFIG.PRODUCTS.INTERNAL_DRAINAGE;

const sortHydraulicSplitOptionByNumber = childOptions => {
  const optionsArray = Object.values(childOptions);

  return optionsArray
    .sort((a, b) => {
      a = parseInt(a.option_key.split('_').pop());
      b = parseInt(b.option_key.split('_').pop());

      return a - b;
    })
    .reduce(
      (accumulator, current) => ({
        ...accumulator,
        [current.option_key]: current
      }),
      {}
    );
};

const getImageFileNameForSystemType = systemTypeSelected => {
  const regexpSlit = CONFIG.IMAGES.HYDRAULIC_SLIT;
  const regexpMini = CONFIG.IMAGES.HYDRAULIC_MINI;
  const regexpEuroSP = CONFIG.IMAGES.HYDRAULIC_EURO_SP;
  const regexpEuro = CONFIG.IMAGES.HYDRAULIC_EURO;

  const regexps = {
    [regexpSlit]: new RegExp(/slit/),
    [regexpMini]: new RegExp(/mini_[0-9]{3}/),
    [regexpEuro]: new RegExp(/euro_[0-9]{3}$/),
    [regexpEuroSP]: new RegExp(/euro_[0-9]{3}_sp/)
  };

  const matchingImageName = Object.keys(regexps).filter(optionKey => 
      regexps[optionKey].test(systemTypeSelected) && optionKey
  )

  return matchingImageName ? matchingImageName[0] : false;
};

const renderImage = systemTypeSelected => {
  const imageFileName = getImageFileNameForSystemType(systemTypeSelected);

  return (
    <div className="row">
      <div className="col-md-2"></div>
      <div className="col-md-10">
        {imageFileName && (
          <ProductImage fileName={imageFileName} />
        )}
      </div>
    </div>
  );
};

const getMeasurementsAsString = (option, measuremenUnit) => {
  const {
    childrenOptions: {
      [`${option.option_key}_height_min`]: { value: HMin },
      [`${option.option_key}_height_max`]: { value: HMax }
    }
  } = option;

  return `(${convertLengthToUserMeasurementUnit(
    HMin,
    measuremenUnit
  )} ${measuremenUnit} - ${convertLengthToUserMeasurementUnit(
    HMax,
    measuremenUnit
  )} ${measuremenUnit})`;
};

const getOptionTitleWithMeasurements = (title, option, measuremenUnit) =>
  `${title} ${getMeasurementsAsString(option, measuremenUnit)}`;

const getTranslationsWithMeasurements = (
  translations,
  option,
  measuremenUnit
) =>
  translations.map(item => ({
    ...item,
    translation: getOptionTitleWithMeasurements(
      item.translation,
      option,
      measuremenUnit
    )
  }));

const attachHeightAndWidthToValues = (childOptions, measuremenUnit) => {
  const optionsArray = Object.values(childOptions);
  const arrayWithAttachments = optionsArray.map(option => {
    const translationsWithMeasurements = getTranslationsWithMeasurements(
      option.titleSourceMessage.translations,
      option,
      measuremenUnit
    );
    const optionsWithAttachedMeasurements = getOptionTitleWithMeasurements(
      option.titleSourceMessage.message,
      option,
      measuremenUnit
    );
    const optionWithAttachment = {
      titleSourceMessage: {
        message: optionsWithAttachedMeasurements,
        translations: translationsWithMeasurements
      }
    };

    return { ...option, ...optionWithAttachment };
  });

  return arrayWithAttachments;
};

const HydraulicSplitOptionsDropdown = ({
  products: {
    [internalDrainageKey]: {
      parentOptions: { [hydraulicSplitKey]: hydraulicSplit }
    }
  },
  user: {
    measurementSystem: { defaultLengthUnit: measuremenUnit }
  },
  mapOptionsToDropdownProps,
  getTranslation,
  onChange,
  [CONFIG.PRODUCT_OPTIONS.HYDRAULIC_SPLIT]: hydraulicSplitSelected
}) => {
  const optionsWithAttachedMeasurements = attachHeightAndWidthToValues(
    hydraulicSplit.childrenOptions,
    measuremenUnit
  );

  return (
    <>
      <Dropdown
        label={getTranslation(hydraulicSplit)}
        name={CONFIG.PRODUCT_OPTIONS.HYDRAULIC_SPLIT}
        options={mapOptionsToDropdownProps({
          data: sortHydraulicSplitOptionByNumber(
            optionsWithAttachedMeasurements
          ),
          dropdownValue: 'option_key'
        })}
        onChange={onChange}
      />
      {renderImage(hydraulicSplitSelected)}
    </>
  );
};

HydraulicSplitOptionsDropdown.defaultProps = {
  products: []
};

export default withData(['products', 'user'])(HydraulicSplitOptionsDropdown);
