import React, { Component } from 'react';
import Modal from 'react-modal';
import Dropzone from 'react-dropzone';

import Translate from './Translate';
import { basicStyles } from '../views/styles/ModalWindow';
import { addNotification } from '../reducers/notifications';
import axiosConfig from '../config/axios';

export default class OrderUploadModules extends Component {
  state = {
    modalIsOpen: false,
    uploadFiles: null
  };

  openModal = () => this.setState({ modalIsOpen: true });

  closeModal = () =>
    this.setState({
      modalIsOpen: false,
      uploadFiles: null
    });

  onNewFileAttachment = ({ target: { files } }) =>
    this.setState({
      uploadFiles: files
    });

  onFileSubmit = event => {
    event.preventDefault();
    const { uploadFiles } = this.state;
    const {
      currentOrder,
      store: { dispatch }
    } = this.props;

    if (!uploadFiles) {
      return false;
    }

    const formData = new FormData();
    uploadFiles.map(file => formData.append('files[]', file));

    formData.append('id', currentOrder.id);

    axiosConfig
      .post(`order-module/upload`, formData, {
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
                message: 'The modules was successfully updated'
              }
            })
          );
          this.closeModal();
        }
      })
      .catch(({ response: { data: { message } } }) => {
        dispatch(
          addNotification({
            fileUpdatedError: {
              className: 'danger',
              message: message
            }
          })
        );
        this.closeModal();
      });
  };

  displaySelectedFiles = () => {
    const { uploadFiles } = this.state;
    return (
      uploadFiles &&
      uploadFiles.map(file => <li key={file.path}>{file.path}</li>)
    );
  };

  render() {
    return (
      <div className="d-inline-block">
        <button onClick={this.openModal} className="dropdown-item btn-primary">
          <i className="fa fa-puzzle-piece"></i>
          <Translate>Modules</Translate>
        </button>
        <Modal
          isOpen={this.state.modalIsOpen}
          onRequestClose={this.closeModal}
          style={basicStyles}
        >
          <h3>
            <Translate>Modules</Translate>
          </h3>
          <div className="alert alert-danger">
            <Translate>
              By uploading a new version of module files you may remove previous
              module data for this order
            </Translate>
          </div>
          <div className="mt-2">
            <div>
              <Translate>Upload/Replace Files</Translate>
            </div>
            <Dropzone
              onDrop={acceptedFiles =>
                this.setState({ uploadFiles: acceptedFiles })
              }
            >
              {({ getRootProps, getInputProps }) => (
                <div>
                  <div {...getRootProps()}>
                    <input {...getInputProps()} />
                    <p className="alert alert-info mt-3">
                      <Translate>
                        Drag 'n' drop some files here, or click to select files
                      </Translate>
                    </p>
                  </div>
                  {this.displaySelectedFiles()}
                </div>
              )}
            </Dropzone>
          </div>
          <div className="d-flex mt-3">
            <button className="btn btn-primary" onClick={this.onFileSubmit}>
              <Translate>Submit</Translate>
            </button>
            <button
              onClick={this.closeModal}
              className="btn btn-secondary ml-auto"
            >
              <Translate>Close</Translate>
            </button>
          </div>
        </Modal>
      </div>
    );
  }
}
