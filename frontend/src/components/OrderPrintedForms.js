import React, { Component } from 'react';
import Modal from 'react-modal';

import Translate from './Translate';
import { basicStyles } from '../views/styles/ModalWindow';

export default class OrderPrintedForms extends Component {
  state = {
    modalIsOpen: false
  };

  openModal = () => this.setState({ modalIsOpen: true });

  closeModal = () =>
    this.setState({
      modalIsOpen: false
    });

  getTemplatePrintUrl = ({
    currentOrder: { id: orderId },
    template: { id: templateId }
  }) => `/printed-form-template/print?order=${orderId}&template=${templateId}`;

  render() {
    const { printedForms, getTranslation, currentOrder } = this.props;
    return (
      <div className="d-inline-block">
        <button onClick={this.openModal} className="dropdown-item">
          <i className="fa fa-file-contract"></i>
          <Translate>Printed Forms</Translate>
        </button>
        <Modal isOpen={this.state.modalIsOpen} style={basicStyles}>
          <h3>
            <Translate>Printed Forms</Translate>
          </h3>

          <div>
            {printedForms &&
              printedForms.map(template => {
                return (
                  <div className="m-2" key={template.id}>
                    <a
                      target="_blank"
                      href={this.getTemplatePrintUrl({
                        currentOrder,
                        template
                      })}
                    >
                      {getTranslation(template)}
                    </a>
                  </div>
                );
              })}
          </div>

          <div className="mt-4 d-flex">
            <button
              onClick={this.closeModal}
              className="btn btn-secondary mr-auto"
            >
              <Translate>Close</Translate>
            </button>
          </div>
        </Modal>
      </div>
    );
  }
}
