class BrandnestorRuleSet {
  constructor(ruleSet, optionName) {
    this.ruleSet = jQuery(ruleSet);
    this.repeatable = this.ruleSet.find('.form-rule').first();
    this.ruleData = [];
    this.rules = [];
    this.optionName = optionName;
  }

  setData(data) {
    this.ruleData = data;
  }

  cleanup() {
    let $rules = this.ruleSet.find('.form-rule');
    $rules.filter((idx, elem) => {
      return !jQuery(elem).is(this.repeatable);
    }).remove();
  }

  hydrate() {
    for (const [index, rule] of Object.entries(this.ruleData)) {
      const $clone = this.repeatable.clone(true).appendTo(this.ruleSet).show();
      this.rules.push(new BrandnestorRule($clone, index, rule.targets, rule.fields, this.optionName));
    }
  }

  onAdd(callback) {
    this.ruleSet.find('.form-rule-add').on('click', callback);
  }

  getSelected() {
    return this.ruleSet.find('select').val();
  }

  next() {
    return this.rules.values();
  }

  [Symbol.iterator]() { return this.next(); }
}

class BrandnestorRule {
  constructor(element, id, ruleTargets, ruleValue, optionName) {
    this.id = id;
    this.element = element;
    this.ruleTargets = ruleTargets;
    this.ruleValue = ruleValue;
    this.optionName = optionName;

    element.find('input[type="checkbox"]').each((index, input) => {
      const oldName = JSON.parse(input.name);

      // FIXME: Input checkbox should be more generic
      if (oldName.menu.indexOf('customize.php') === -1 && (oldName.parent === undefined || oldName.parent !== oldName.menu)) {
        input.disabled = false;

        input.name = `${optionName}[${this.id}][fields][${oldName.menu}]`;

        const slug = oldName.menu
        const value = this.ruleValue[`${slug}`] || this.ruleValue[`${decodeURIComponent(slug)}`] || null;

        if (!value) {
          input.checked = false;
        } else if (value !== 'on') {
          input.value = value;
          jQuery(input).closest('.form-group').find('.editable').first().html(value);
        }
      }
    });

    element.find('select').each((index, input) => {
      const name = input.name;
      input.name = `${optionName}[${this.id}][fields][${name}]`;
      input.disabled = false;
      input.value = this.ruleValue[`${name}`];
    });

    for (const [i, target] of ruleTargets.entries()) {
      const hiddenInput = document.createElement('input');
      hiddenInput.setAttribute('hidden', true);
      hiddenInput.setAttribute('name', `${optionName}[${this.id}][targets][${i}]`);
      hiddenInput.setAttribute('value', target);
      element.append(hiddenInput);

      const [targetType, targetValue] = target.split(':');
      const label = document.createElement('span');
      label.innerHTML = targetValue;
      label.classList.add('label');
      label.classList.add(targetType);
      element.find('.form-rule-head').append(label);
    }
  }

  onDelete(callback) {
    this.element.find('.form-rule-delete > button').on('click', () => {
      callback().then(() => this.element.remove());
    });
  }
}

function createRuleSet(ruleSetElement, optionName) {
  return new BrandnestorRuleSet(ruleSetElement, optionName);
}

export { createRuleSet };
