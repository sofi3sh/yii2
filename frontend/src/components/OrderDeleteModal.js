import React, { Component } from 'react';
import Modal from 'react-modal';
import Translate from './Translate';
import axiosConfig from '../config/axios';
import { basicStyles } from '../views/styles/ModalWindow';
import { addNotification } from '../reducers/notifications';

export default class OrderDeleteModal extends Component {
  constructor() {
    super();

    this.state = {
      modalIsOpen: false
    };

    this.openModal = this.openModal.bind(this);
    this.closeModal = this.closeModal.bind(this);
  }

  openModal() {
    this.setState({ modalIsOpen: true });
  }

  closeModal() {
    this.setState({
      modalIsOpen: false
    });
  }

  onOrderDelete() {
    const {
      currentOrder: { id },
      handleSuccessDelete,
      store: { dispatch }
    } = this.props;
    axiosConfig
      .delete('/order-form/delete', {
        data: { id }
      })
      .then(({ data: { success } }) => {
        if (success) {
          dispatch(
            addNotification({
              orderDeleted: {
                className: 'success',
                message: 'The order was deleted'
              }
            })
          );
          handleSuccessDelete();
          this.closeModal();
        }
      })
      .catch(() => {
        dispatch(
          addNotification({
            orderDeletedError: {
              className: 'danger',
              message: 'Something went wrong'
            }
          })
        );
        this.closeModal();
      });
  }

  render() {
    return (
      <div className="d-inline-block">
        <button onClick={this.openModal} className="dropdown-item btn-danger">
          <i className="fa fa-trash"></i>
          <Translate>Delete</Translate>
        </button>
        <Modal
          isOpen={this.state.modalIsOpen}
          onRequestClose={this.closeModal}
          style={basicStyles}
        >
          <h3>
            <Translate>Do you want to delete this order?</Translate>
          </h3>

          <div className="mt-4 d-flex">
            <button
              onClick={this.closeModal}
              className="btn btn-secondary mr-auto"
            >
              <Translate>Close</Translate>
            </button>

            <button
              className="btn btn-primary"
              onClick={() => this.onOrderDelete()}
            >
              <Translate>Yes</Translate>
            </button>
          </div>
        </Modal>
      </div>
    );
  }
}
