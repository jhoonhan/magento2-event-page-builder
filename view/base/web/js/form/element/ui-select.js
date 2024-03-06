"use strict";
define([
    'underscore',
    'Magento_Ui/js/form/element/ui-select',
    'Magento_Ui/js/lib/key-codes',
    'mage/translate',
    'ko',
    'jquery',
    'Magento_Ui/js/lib/view/utils/async',
], function (_, UiSelect, keyCodes, $t, ko, $) {
    'use strict';
    return UiSelect.extend({
        defaults: {
            speakerFields: ko.observableArray([]),
            speakers: [],
            scheduleFormData: {
                id: null,
                event_id: null,
            },
            isExpanded: ko.observable(false),
        },
        initialize: function (config, element) {
            this._super();
            const url = window.location.href;
            const scheduleIdMatch = url.match(/\/schedule_id\/(\d+)/);
            const eventIdMatch = url.match(/\/event_id\/(\d+)/);
            this.scheduleFormData.id = scheduleIdMatch ? +scheduleIdMatch[1] : null;
            this.scheduleFormData.event_id = eventIdMatch ? +eventIdMatch[1] : null;
            this.addSaveDisabler();
            this.sanitizeValue();
            return this;
        },
        sanitizeValue: function () {
            const hashmap = [];
            const options = this.options();
            const values = this.value();
            for (let i = 0; i < options.length; i++) {
                hashmap.push(options[i].value);
            }
            values.forEach((val, i) => {
                if (!hashmap.includes(val)) {
                    values.splice(i, 1);
                }
            });
            this.value(values);
        },
        addSaveDisabler: function () {
            const buttonContainer = document.querySelector(".page-actions-buttons");
            const saveDisableHtml = `
                <button id="save-disabler" title="Custom Save-1" type="button" class="action- scalable disabled save primary" style="display: none;">
<!--                TBF: i18n not working         -->
                    <span data-bind="i18n: 'Please Save Speakers'">Please Save Speakers</span>
                </button>
                `;
            buttonContainer.insertAdjacentHTML('beforeend', saveDisableHtml);
        },
        toggleExpanded: function (option) {
            const saveDisablerButton = document.getElementById('save-disabler');
            const saveButton = document.getElementById('save');
            if (option) {
                saveButton.style.display = 'none';
                saveDisablerButton.style.display = 'inline-block';
            }
            else {
                saveDisablerButton.style.display = 'none';
                saveButton.style.display = 'inline-block';
            }
            this.isExpanded(option);
        },
        addField: function () {
            this.speakerFields.push({
                event_id: this.scheduleFormData.event_id,
                firstname: ko.observable(''),
                lastname: ko.observable(''),
                title: ko.observable(''),
                company: ko.observable(''),
                description: ko.observable(''),
            });
            this.toggleExpanded(true);
        },
        removeField: function (field) {
            this.speakerFields.remove(field);
            if (this.speakerFields().length === 0) {
                this.toggleExpanded(false);
            }
        },
        addItemsToOptions: function (data) {
            const { items, itemIds } = data;
            const newOptions = items.map((item) => {
                return {
                    label: `${item.lastname}, ${item.firstname} | ${item.title}, ${item.company}`,
                    isVisited: false,
                    level: 1,
                    path: "",
                    true: 1,
                    value: item.speaker_id,
                    visible: false,
                };
            });
            this.options([...this.options(), ...newOptions]);
            this.value([...this.value(), ...itemIds]);
            this.speakerFields([]);
        },
        getSpeakerFormData: function (data) {
            const fieldSets = data.querySelectorAll(".admin__fieldset");
            const returnData = [];
            for (let i = 0; i < fieldSets.length; i++) {
                const inputs = fieldSets[i].querySelectorAll("input");
                const textareas = fieldSets[i].querySelectorAll("textarea");
                const speakerData = {
                    event_id: this.scheduleFormData.event_id,
                    firstname: '',
                    lastname: '',
                    title: '',
                    company: '',
                    description: '',
                };
                for (let j = 0; j < inputs.length; j++) {
                    speakerData[inputs[j].name] = inputs[j].value;
                }
                for (let j = 0; j < textareas.length; j++) {
                    const textarea = textareas[j];
                    speakerData[textarea.name] = textarea.value;
                }
                returnData.push(speakerData);
            }
            return returnData;
        },
        addNew: function (data) {
            const self = this;
            $.ajax({
                url: 'https://hayward.dev/admin/eventpagebuilder/speaker/addspeaker',
                type: 'POST',
                data: { items: data },
                showLoader: true,
                success: function (response) {
                    self.addItemsToOptions({
                        items: response.items,
                        itemIds: response.itemIds
                    });
                    self.toggleExpanded(false);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert(`Error occurred.`);
                }
            });
        },
        uploadImage: (data) => {
            const input = document.getElementById('image-upload');
            const preview = document.getElementById('image-preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Uploaded Image">';
                };
                reader.readAsDataURL(input.files[0]);
            }
        },
        handleSubmit: function (data) {
            const speakerFormData = this.getSpeakerFormData(data);
            this.addNew(speakerFormData);
        }
    });
});
//# sourceMappingURL=ui-select.js.map