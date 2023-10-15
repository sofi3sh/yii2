import React, { Component } from 'react';
import Translate from './Translate';
import CONFIG from '../config/app';
import {
  Accordion,
  AccordionItem,
  AccordionItemHeading,
  AccordionItemPanel,
  AccordionItemButton
} from 'react-accessible-accordion';

const styles = {
  dropdownArrow: {
    position: 'absolute',
    top: '50%',
    right: '1.25rem',
    marginTop: '-.5rem'
  }
};

export default class OrderFilesAccordion extends Component {
  getFileProductOptions() {
    const { products, currentOrder } = this.props;
    if (!products) {
      return false;
    }
    const orderProduct = products[currentOrder.product.product_key];
    return Object.values(orderProduct.options).filter(
      option => option.option_type == CONFIG.PRODUCT_OPTION_FILE_TYPE_ID
    );
  }

  getExistingFileForProductOption(productOption) {
    return Object.values(
      this.props.currentOrder.files.filter(
        file => file.file_type_id == productOption.fileType.id
      )
    )[0];
  }

  checkFileAccess({ file: { file_type_id }, action }) {
    const {
      user: { fileAccessRules },
      currentOrder: { status_id }
    } = this.props;
    return fileAccessRules.filter(
      ({
        file_type_id: accessRuleFileType,
        action_id,
        status_id: fileAccessStatusId
      }) =>
        accessRuleFileType == file_type_id &&
        action_id == CONFIG.FILE_ACCESS_ACTIONS[action] &&
        fileAccessStatusId == status_id
    )[0];
  }

  render() {
    const {
      products,
      getTranslation,
      onNewFileAttachment,
      onFileSubmit
    } = this.props;
    return (
      <Accordion>
        {products &&
          this.getFileProductOptions().map(productOption => {
            const existingFile = this.getExistingFileForProductOption(
              productOption
            );
            return (
              <div className="row" key={productOption.id}>
                <AccordionItem className="w-100 p-2 m-2 mb-2">
                  <AccordionItemHeading className="btn border-primary border-2 w-100">
                    <AccordionItemButton className="w-100 d-flex">
                      <div>{getTranslation(productOption.fileType)}</div>{' '}
                      <i
                        className="fa fa-arrow-alt-circle-down mr-auto"
                        style={styles.dropdownArrow}
                      ></i>
                    </AccordionItemButton>
                  </AccordionItemHeading>
                  <AccordionItemPanel>
                    {existingFile &&
                      this.checkFileAccess({
                        file: existingFile,
                        action: 'view'
                      }) && (
                        <div className="mt-2">
                          <a
                            target="_blank"
                            className="btn btn-primary mb-3"
                            href={`/file/view/${existingFile.id}`}
                          >
                            <Translate>View File</Translate>:
                            {existingFile.full_origin_name}
                          </a>
                        </div>
                      )}
                    {existingFile &&
                      this.checkFileAccess({
                        file: existingFile,
                        action: 'edit'
                      }) && (
                        <div className="mt-2">
                          <div>
                            <Translate>Upload/Replace File</Translate>
                          </div>
                          <input
                            type="file"
                            className="form-control"
                            onChange={({ target }) =>
                              onNewFileAttachment({ target, productOption })
                            }
                          />
                          <button
                            className="btn btn-primary mt-2"
                            onClick={onFileSubmit}
                          >
                            <Translate>Submit</Translate>
                          </button>
                        </div>
                      )}
                  </AccordionItemPanel>
                </AccordionItem>
              </div>
            );
          })}
      </Accordion>
    );
  }
}
