import React, { Component } from 'react';
import classNames from 'classnames';
import Translate from '../../Translate';

class SaveForm extends Component {
  getMessage = () =>
    this.props.submitSucceeded
      ? 'The changes were successfully saved'
      : 'Something went wrong';

  render() {
    const { submitSucceeded } = this.props;
    return (
      <div
        className={classNames('alert p-5 text-center', {
          'alert-success': submitSucceeded,
          'alert-danger': !submitSucceeded
        })}
      >
        <Translate>{this.getMessage()}</Translate>
        <div className="pt-3">
          <a href="/order/index" className="btn btn-primary">
            <Translate>List of orders</Translate>
          </a>
        </div>
      </div>
    );
  }
}

export default SaveForm;
