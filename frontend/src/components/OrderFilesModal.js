import React, { Component } from 'react';
import Modal from 'react-modal';
import Translate from './Translate';
import { basicStyles } from '../views/styles/ModalWindow';
import { addNotification } from '../reducers/notifications';
import axiosConfig from '../config/axios';
import OrderFilesAccordion from './OrderFilesAccordion';

export default class OrderFilesModal extends Component {
  constructor() {
    super();

    this.state = {
      modalIsOpen: false,
      files: [],
      uploadFile: {
        optionKey: null,
        file: null
      }
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

  onNewFileAttachment = ({
    target: { files },
    productOption: { option_key }
  }) =>
    this.setState({
      uploadFile: {
        optionKey: option_key,
        file: files[0]
      }
    });

  onFileSubmit = event => {
    event.preventDefault();
    const { uploadFile } = this.state;
    const {
      currentOrder,
      store: { dispatch }
    } = this.props;

    const formData = new FormData();
    formData.append(uploadFile.optionKey, uploadFile.file);
    formData.append('id', currentOrder.id);

    axiosConfig
      .post(`order-form/update`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(({ data: { success } }) => {
        if (success) {
          dispatch(
            addNotification({
              fileUpdated: {
                className: 'success',
                message: 'The file was successfully updated'
              }
            })
          );
          this.closeModal();
        }
      })
      .catch(() => {
        dispatch(
          addNotification({
            fileUpdatedError: {
              className: 'danger',
              message: 'Something went wrong'
            }
          })
        );
        this.closeModal();
      });
  };

  render() {
    return (
      <div className="d-inline-block">
        <button onClick={this.openModal} className="dropdown-item btn-primary">
          <i className="fa fa-file-alt"></i>
          <Translate>Files</Translate>
        </button>
        <Modal
          isOpen={this.state.modalIsOpen}
          onRequestClose={this.closeModal}
          style={basicStyles}
        >
          <h3>
            <Translate>Files</Translate>
          </h3>
          <OrderFilesAccordion
            {...this.props}
            onNewFileAttachment={this.onNewFileAttachment}
            onFileSubmit={this.onFileSubmit}
          />
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
