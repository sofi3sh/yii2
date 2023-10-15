import React, { Component } from 'react';

import CONFIG from '../../../config/app';
import HydraulicGrilleTypeDropdown from '../../reduxForm/HydraulicGrilleTypeDropdown';
import InputFile from '../../reduxForm/InputFile';
import Checkbox from '../../reduxForm/Checkbox';
import { grilleAdjustmentCheckboxes } from '../../validation/validationRules';

const internalDrainageKey = CONFIG.PRODUCTS.INTERNAL_DRAINAGE;
const productOptionKeys = CONFIG.PRODUCT_OPTIONS;

class HydraulicSplitGrilleParams extends Component {
  componentDidMount() {
    this.setGrilleTypeInitValue();
  }

  isValidated() {
    return this.props.valid;
  }

  setGrilleTypeInitValue() {
    const {
      order: {
        orderProductOptions: {
          [productOptionKeys.HYDRAULIC_GRILLE_TYPE]: grilleType
        }
      }
    } = this.props;
    this.props.change(
      productOptionKeys.HYDRAULIC_GRILLE_TYPE,
      (grilleType && grilleType.option_key) ||
        productOptionKeys.HYDRAULIC_GRILLE_TYPE_PERFORATED
    );
  }

  toggleNonStandardGrilleFile = () =>
    this.props[productOptionKeys.HYDRAULIC_GRILLE_TYPE] ===
    productOptionKeys.HYDRAULIC_GRILLE_TYPE_NON_STANDARD;

    toggleGrilleAdjustment = () =>
    this.props[productOptionKeys.HYDRAULIC_GRILLE_TYPE] !==
    productOptionKeys.HYDRAULIC_GRILLE_TYPE_WITHOUT_GRILLE;

  render() {
    const {
      products: {
        [internalDrainageKey]: { parentOptions }
      },
      getTranslation,
      files: {
        orderFiles: {
          [productOptionKeys.HYDRAULIC_GRILLE_NON_STANDARD_FILE]: grilleNonStandardFile
        }
      }
    } = this.props;
    const {
      [productOptionKeys.HYDRAULIC_GRILLE_TYPE]: {
        childrenOptions: {
          [productOptionKeys.HYDRAULIC_GRILLE_TYPE_NON_STANDARD]: {
            childrenOptions: nonStandardGrilleChildrenOptions
          }
        }
      }
    } = parentOptions;
    return (
      <div>
        <HydraulicGrilleTypeDropdown {...this.props} />
        {this.toggleNonStandardGrilleFile() && (
          <InputFile
            label={getTranslation(
              nonStandardGrilleChildrenOptions[
                productOptionKeys.HYDRAULIC_GRILLE_NON_STANDARD_FILE
              ]
            )}
            name={productOptionKeys.HYDRAULIC_GRILLE_NON_STANDARD_FILE}
            existingFile={grilleNonStandardFile}
          />
        )}
        {this.toggleGrilleAdjustment() && (
          <React.Fragment>
            <Checkbox
              label={getTranslation(
                parentOptions[
                  productOptionKeys.HYDRAULIC_GRILLE_ADJUSTMENT_CUSTOMER
                ]
              )}
              name={productOptionKeys.HYDRAULIC_GRILLE_ADJUSTMENT_CUSTOMER}
              validate={[grilleAdjustmentCheckboxes]}
            />
            <Checkbox
              label={getTranslation(
                parentOptions[
                  productOptionKeys.HYDRAULIC_GRILLE_ADJUSTMENT_MANUFACTURE
                ]
              )}
              name={productOptionKeys.HYDRAULIC_GRILLE_ADJUSTMENT_MANUFACTURE}
            />
          </React.Fragment>
        )}
      </div>
    );
  }
}

export default HydraulicSplitGrilleParams;
