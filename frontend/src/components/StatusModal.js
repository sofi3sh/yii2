import React, { Component } from 'react';
import Modal from 'react-modal';
import Translate from './Translate';
import axiosConfig from '../config/axios';
import { basicStyles } from '../views/styles/ModalWindow';
import { addNotification } from '../reducers/notifications';
import Dropdown from './form/Dropdown';
import Textarea from './form/Textarea';
import APP_CONFIG from '../config/app';

Modal.setAppElement('#order-list');

const styles = {
  loaderWrapper: {
    width: '85%',
    position: 'absolute',
    height: '60%',
    backgroundColor: 'rgba(255, 255, 255, 0.9)',
    zIndex: 100,
    display: 'flex',
    justifyContent: 'center',
    flexDirection: 'column',
    textAlign: 'center'
  }
};

class StatusModal extends Component {
  state = {
    modalIsOpen: false,
    currentStatus: null,
    changeNextStatusTo: null,
    previousStatus: null,
    isLoading: false,
    comment: {
      reasonId: null,
      comment: null
    }
  };

  componentDidMount() {
    const {
      currentOrder: { status, previousStatus }
    } = this.props;

    this.setState({
      currentStatus: status,
      previousStatus
    });
  }

  openModal = () => {
    if (this.state.currentStatus.key === APP_CONFIG.STATUSES.IN_WORK) {
      return false;
    }

    this.setState({ modalIsOpen: true });
    this.setChangeStateNextStatus();
  };

  closeModal = () => this.setState({ modalIsOpen: false });

  getAllNextStatuses() {
    const { statuses } = this.props;
    const { currentStatus } = this.state;

    if (currentStatus && statuses) {
      const {
        [currentStatus.key]: { nextStatuses }
      } = statuses;
      return nextStatuses;
    }

    return false;
  }

  getNextStatus = nextStatuses => Object.values(nextStatuses)[0].nextStatus;

  setChangeStateNextStatus = () => {
    const nextStatuses = this.getAllNextStatuses();
    const nextStatus = Object.values(nextStatuses)[0];
    if (nextStatus && nextStatus.nextStatus) {
      const {
        nextStatus: { key }
      } = nextStatus;
      this.setState({
        changeNextStatusTo: key
      });
    }
  };

  getCurrentStatusColor() {
    const { currentStatus } = this.state;

    if (currentStatus) {
      return currentStatus.color;
    }

    return '#ccc';
  }

  onNextStatusDropdownChange = ({ target: { value } }) => {
    this.setState({ changeNextStatusTo: value });
  };

  onCommentChange = ({ target: { value, name } }) =>
    this.setState(({ comment }) => ({
      comment: { ...comment, ...{ [name]: value } }
    }));

  renderCommentSection = () => {
    const { changeNextStatusTo } = this.state;
    if (!changeNextStatusTo) {
      return false;
    }
    const { statuses, mapOptionsToDropdownProps } = this.props;
    let {
      [changeNextStatusTo]: { allow_comment: allowComment, commentReasons }
    } = statuses;
    allowComment = !!+allowComment;

    return (
      <div className="mt-1">
        {allowComment && commentReasons.length !== 0 && (
          <div>
            <Dropdown
              label={'Reason'}
              name="reasonId"
              onChange={this.onCommentChange}
              options={mapOptionsToDropdownProps({
                data: commentReasons,
                dropdownValue: 'id'
              })}
            />
          </div>
        )}
        {allowComment && (
          <Textarea
            label="Comment"
            name="comment"
            onChange={this.onCommentChange}
          />
        )}
      </div>
    );
  };

  renderNextStatusSection = () => {
    const nextStatuses = this.getAllNextStatuses();
    const { getTranslation } = this.props;
    const nextStatusesCount = Object.keys(nextStatuses).length;

    if (!nextStatuses || !nextStatusesCount) {
      return false;
    }
    const nextOrderStatus = this.getNextStatus(nextStatuses);
    if (!nextOrderStatus) {
      return false;
    }

    if (nextStatusesCount === 1) {
      return (
        <React.Fragment>
          <div className="col-form-label">
            <Translate>Next Status</Translate>:{' '}
            {getTranslation(nextOrderStatus)}
          </div>
          {this.renderCommentSection()}
        </React.Fragment>
      );
    }

    return (
      <React.Fragment>
        <div className="col-form-label">
          <Translate>Next Status</Translate>:{' '}
        </div>

        <div>
          <select
            className="form-control"
            defaultValue={nextOrderStatus.key}
            onChange={event => this.onNextStatusDropdownChange(event)}
          >
            {Object.values(nextStatuses).map(({ nextStatus }) => {
              if (!nextStatus) {
                return null;
              }
              return (
                <option value={nextStatus.key} key={nextStatus.key}>
                  {getTranslation(nextStatus)}
                </option>
              );
            })}
          </select>
        </div>
        {this.renderCommentSection()}
      </React.Fragment>
    );
  };

  onStatusChange = apiAction => {
    const {
      currentOrder: { id: orderId },
      statuses,
      store: { dispatch }
    } = this.props;
    this.setState({ isLoading: true });
    axiosConfig
      .post(`/order-status/${apiAction}`, {
        orderId,
        nextStatus: this.state.changeNextStatusTo,
        comment: this.state.comment
      })
      .then(({ data: { newStatus } }) => {
        this.closeModal();
        dispatch(
          addNotification({
            statusChanged: {
              className: 'success',
              message: "The order's status was updated"
            }
          })
        );
        this.props.onStatusChange(newStatus);
        return this.setState({
          currentStatus: statuses[newStatus.key],
          previousStatus: this.state.currentStatus,
          isLoading: false
        });
      })
      .catch(data => {
        const { status, message } = data;
        this.closeModal();
        dispatch(
          addNotification({
            statusChanged: {
              className: 'danger',
              message: "You don't have permision to switch the order to previous status or this status doesn't exist"
            }
          })
        );
        return this.setState({
          isLoading: false
        })
      });
  };

  render() {
    const {
      currentOrder: { uuid, status },
      getTranslation
    } = this.props;
    const { currentStatus, previousStatus, isLoading } = this.state;
    const nextStatuses = this.getAllNextStatuses();
    return (
      <div>
        <button
          onClick={this.openModal}
          className="btn w-100 "
          style={{
            fontSize: '12px',
            color: '#fff',
            background: this.getCurrentStatusColor()
          }}
        >
          {currentStatus && getTranslation(currentStatus)}
        </button>
        <Modal
          isOpen={this.state.modalIsOpen}
          onRequestClose={this.closeModal}
          style={basicStyles}
        >
          <h3>
            <Translate>Order #</Translate> : {uuid}
          </h3>
          {isLoading && (
            <div style={styles.loaderWrapper}>
              <div>
                <Translate>Loading...</Translate>
              </div>
            </div>
          )}
          <div>
            <Translate>Current Status</Translate>:{' '}
            {currentStatus && getTranslation(currentStatus)}
          </div>

          {nextStatuses && (
            <div className="row form-group">
              <div className="col-12">{this.renderNextStatusSection()}</div>
            </div>
          )}

          <div className="mt-4 d-flex">
            <button
              onClick={this.closeModal}
              className="btn btn-secondary mr-auto"
            >
              <Translate>Close</Translate>
            </button>
            {previousStatus && (
              <button
                className="btn btn-primary mr-2"
                onClick={() => this.onStatusChange('previous')}
                disabled={isLoading}
              >
                <Translate>Previous</Translate>
              </button>
            )}

            {!!Object.keys(nextStatuses).length && (
              <button
                className="btn btn-primary"
                onClick={() => this.onStatusChange('next')}
                disabled={isLoading}
              >
                <Translate>Next</Translate>
              </button>
            )}
          </div>
        </Modal>
      </div>
    );
  }
}

export default StatusModal;
