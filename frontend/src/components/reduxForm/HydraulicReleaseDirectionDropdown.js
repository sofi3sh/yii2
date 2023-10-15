import React from 'react';

import Dropdown from './Dropdown';
import withData from '../../hocs/withData';
import CONFIG from '../../config/app';
import ProductImage from '../ProductImage';

const internalDrainageKey = CONFIG.PRODUCTS.INTERNAL_DRAINAGE;
const releaseDirectionKey = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_RELEASE_DIRECTION;

const renderImages = (drainageTypeSelected, releaseDirectionSelected) => (
  <div className="row">
    <div className="col-md-2"></div>
    <div className="col-md-10">
      <ProductImage
        fileName={`${drainageTypeSelected}.${releaseDirectionSelected}.png`}
      />
    </div>
  </div>
);

const HydraulicReleaseDirectionDropdown = ({
  products: {
    [internalDrainageKey]: {
      parentOptions: { [releaseDirectionKey]: releaseDirection }
    }
  },
  mapOptionsToDropdownProps,
  getTranslation,
  [CONFIG.PRODUCT_OPTIONS.HYDRAULIC_DRAINAGE_TYPE]: drainageTypeSelected,
  [CONFIG.PRODUCT_OPTIONS.HYDRAULIC_RELEASE_DIRECTION]: releaseDirectionSelected
}) => {
  return (
    <React.Fragment>
      <Dropdown
        label={getTranslation(releaseDirection)}
        name={releaseDirectionKey}
        options={mapOptionsToDropdownProps({
          data: releaseDirection.childrenOptions,
          dropdownValue: 'option_key'
        })}
      />
      {renderImages(drainageTypeSelected, releaseDirectionSelected)}
    </React.Fragment>
  );
};

HydraulicReleaseDirectionDropdown.defaultProps = {
  products: []
};

export default withData(['products'])(HydraulicReleaseDirectionDropdown);
