import React, { Component } from 'react';
import { connect } from 'react-redux';
import classNames from 'classnames';
import { removeNotification } from '../reducers/notifications';
import Translate from './Translate';

const styles = {
  wrapper: {
    position: 'fixed',
    right: '35%',
    top: '15%',
    zIndex: 9999,
    width: '40%'
  }
};

class Notification extends Component {
  render() {
    const { notifications } = this.props;
    return (
      <React.Fragment>
        {Object.keys(notifications).map(notificationName => {
          const {
            [notificationName]: { message, className: colorClass },
            [notificationName]: notificationData
          } = notifications;
          return (
            notificationData && (
              <div
                className={classNames('alert', {
                  [`alert-${colorClass}`]: colorClass
                })}
                key={notificationName}
                style={styles.wrapper}
              >
                <Translate>{message}</Translate>

                <button
                  className="close"
                  onClick={() => {
                    this.props.store.dispatch(
                      removeNotification(notificationName)
                    );
                  }}
                >
                  <i className="fa fa-trash"></i>
                </button>
              </div>
            )
          );
        })}
      </React.Fragment>
    );
  }
}

const mapStateToProps = state => {
  return {
    notifications: state.notifications
  };
};

export default connect(mapStateToProps)(Notification);
