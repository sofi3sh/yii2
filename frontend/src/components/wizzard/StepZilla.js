import React from 'react';
import StepZilla from 'react-stepzilla';

export default class StepZillaExtended extends StepZilla {
  onStepChange = (invalid, key) => !invalid && this.jumpToStep(key);

  renderSteps = () => {
    return this.props.steps.map((step, key) => {
      const {
        component: {
          props: { invalid }
        }
      } = step;
      const {
        steps: {
          [key]: { name }
        }
      } = this.props;
      return (
        <li
          className={this.getClassName('progtrckr', key)}
          onClick={() => this.onStepChange(invalid, key)}
          key={key}
          value={key}
        >
          <div onClick={() => this.onStepChange(invalid, key)}>
            <div className="icon">
              <i className="fa fa-pen text-center"></i>
            </div>

            <div className="text">{name}</div>
          </div>
        </li>
      );
    });
  };
}
