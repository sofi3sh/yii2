import React, { Component } from 'react';

import InputFile from '../../reduxForm/InputFile';
import CONFIG from '../../../config/app';
import { required } from '../../validation/validationRules';

const bridgeTrayKey = CONFIG.PRODUCTS.BRIDGE_TRAY;
const productOptionKeys = CONFIG.PRODUCT_OPTIONS;

class BridgeTrayFiles extends Component {
  isValidated() {
    return this.props.valid;
  }

  render() {
    const {
      products: {
        [bridgeTrayKey]: { parentOptions }
      },
      getTranslation,
      files: {
        orderFiles: { [productOptionKeys.BRIDGE_TRAY_FILE]: bridgeTrayFile }
      }
    } = this.props;

    return (
      <div>
        <InputFile
          label={getTranslation(
            parentOptions[productOptionKeys.BRIDGE_TRAY_FILE]
          )}
          name={productOptionKeys.BRIDGE_TRAY_FILE}
          existingFile={bridgeTrayFile}
          validate={[required]}
        />
      </div>
    );
  }
}

export default BridgeTrayFiles;
