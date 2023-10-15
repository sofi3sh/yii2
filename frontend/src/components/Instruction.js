import React, { Component } from 'react';

import Translate, { manageTranslation } from './Translate';
import Modal from 'react-modal';
import { basicStyles } from '../views/styles/ModalWindow';
import withData from '../hocs/withData';

Modal.setAppElement('#order-form');

class Instruction extends Component {
  state = {
    modalIsOpen: false
  };

  openModal = () => this.setState({ modalIsOpen: true });

  closeModal = () => this.setState({ modalIsOpen: false });

  getFormattedMessage = () => {
    const { message, translations } = this.props;
    if (!message) {
      return false;
    }
    const translatedMessage = manageTranslation(message, translations);
    return translatedMessage.split('<br>').map((message, key) => {
      return (
        <React.Fragment key={key}>
          {message}
          <br />
        </React.Fragment>
      );
    });
  };

  render() {
    const { message } = this.props;
    return (
      <div className="d-inline-block">
        {message && (
          <button
            onClick={event => {
              event.preventDefault();
              this.openModal();
            }}
            className="dropdown-item"
          >
            <i className="fa fa-question-circle"></i>
          </button>
        )}
        <Modal
          isOpen={this.state.modalIsOpen}
          onRequestClose={this.closeModal}
          style={basicStyles}
        >
          <h6 className="text-center">{this.getFormattedMessage()}</h6>

          <div className="mt-4 text-center">
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

export default withData(['translations'], {
  translations: {
    controller: 'order-list'
  }
})(Instruction);
