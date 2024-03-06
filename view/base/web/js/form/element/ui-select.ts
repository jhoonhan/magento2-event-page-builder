//
// K009: This file is a custom UI component for the admin panel. It is a select element that allows the user to add speakers to a schedule. It is used in the schedule form in the admin panel.
//
interface SpeakerData {
    event_id: string;
    firstname: string;
    lastname: string;
    title: string;
    company: string;
    description: string;
}

interface SpeakerDataWithId extends SpeakerData {
    speaker_id: number;
}


// @ts-ignore
define([
    'underscore',
    'Magento_Ui/js/form/element/ui-select',
    'Magento_Ui/js/lib/key-codes',
    'mage/translate',
    'ko',
    'jquery',
    'Magento_Ui/js/lib/view/utils/async',

], function (_: any, UiSelect: any, keyCodes: any, $t: any, ko: any, $: any) {
    'use strict';

    // Extend UiSelect component to add custom functionality
    return UiSelect.extend({
        defaults: {
            speakerFields: ko.observableArray([]),
            speakers: [],
            scheduleFormData: {
                id: null,
                event_id: null,
                // name: null,
                // schedule_date: null,
                // starts_at: null,
                // ends_at: null,
                // speakers: null,
                // is_published: null
            },
            isExpanded: ko.observable(false),
        },

        initialize: function (config: any, element: any) {
            this._super();
            // Gets the field's initial value
            // this.speakers = this.getInitialValue();
            // console.log(this.speakers);
            // this.speakers = [1];
            // Extract ids
            const url = window.location.href;
            const scheduleIdMatch = url.match(/\/schedule_id\/(\d+)/);
            const eventIdMatch = url.match(/\/event_id\/(\d+)/);
            this.scheduleFormData.id = scheduleIdMatch ? +scheduleIdMatch[1] : null;
            this.scheduleFormData.event_id = eventIdMatch ? +eventIdMatch[1] : null;

            this.addSaveDisabler();
            this.sanitizeValue();

            return this;
        },
        sanitizeValue: function (): void {
            // If speaker is deleted from frontend but not changed in database, it checks if the value is in the options and if not remove it from the value so that when the schedule is saved, it doesn't try to save a speaker that doesn't exist.
            const hashmap: string[] = [];
            const options: any[] = this.options();
            const values: string[] = this.value();
            for (let i: number = 0; i < options.length; i++) {
                hashmap.push(options[i].value);
            }
            values.forEach((val: string, i: number) => {
                if (!hashmap.includes(val)) {
                    values.splice(i, 1);
                }
            });
            this.value(values);
        },
        addSaveDisabler: function (): void {
            const buttonContainer: Element | null = document.querySelector(".page-actions-buttons");
            const saveDisableHtml: string = `
                <button id="save-disabler" title="Custom Save-1" type="button" class="action- scalable disabled save primary" style="display: none;">
<!--                TBF: i18n not working         -->
                    <span data-bind="i18n: 'Please Save Speakers'">Please Save Speakers</span>
                </button>
                `;
            buttonContainer!.insertAdjacentHTML('beforeend', saveDisableHtml);
        },
        toggleExpanded: function (option: boolean): void {
            // This is to force the user flow so that user must save speakers before saving schedule.
            // It toggles between two buttons, one that is disabled and one that is enabled.
            // It also shows and hide save speakers button.

            const saveDisablerButton: HTMLElement | null = document.getElementById('save-disabler');
            const saveButton: HTMLElement | null = document.getElementById('save');
            if (option) {
                saveButton!.style.display = 'none';
                saveDisablerButton!.style.display = 'inline-block';
            } else {
                saveDisablerButton!.style.display = 'none';
                saveButton!.style.display = 'inline-block';
            }
            this.isExpanded(option);
        },

        // Frontend
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
        removeField: function (field: Element): void {
            this.speakerFields.remove(field);
            // If no fields, show save button
            if (this.speakerFields().length === 0) {
                this.toggleExpanded(false);
            }
        },
        addItemsToOptions: function (data: any): void {
            // Add newly saved items to options frontend
            const {items, itemIds}: { items: any[], itemIds: string[] } = data;
            const newOptions = items.map((item: SpeakerDataWithId) => {
                return {
                    label: `${item.lastname}, ${item.firstname} | ${item.title}, ${item.company}`,
                    isVisited: false,
                    level: 1,
                    path: "",
                    true: 1,
                    value: item.speaker_id,
                    visible: false,
                }
            });
            this.options([...this.options(), ...newOptions]);
            this.value([...this.value(), ...itemIds]);
            this.speakerFields([]);

        },


        // Backend
        getSpeakerFormData: function (data: HTMLFormElement): SpeakerData[] {
            // Grabs all the fieldsets
            const fieldSets: NodeListOf<HTMLFieldSetElement> = data.querySelectorAll(".admin__fieldset");
            const returnData: SpeakerData[] = [];

            // Iterate through to create speaker data to send to backend
            for (let i = 0; i < fieldSets.length; i++) {
                const inputs: NodeListOf<HTMLInputElement> = fieldSets[i].querySelectorAll("input");
                const textareas: NodeListOf<HTMLTextAreaElement> = fieldSets[i].querySelectorAll("textarea");

                const speakerData: SpeakerData = {
                    event_id: this.scheduleFormData.event_id,
                    firstname: '',
                    lastname: '',
                    title: '',
                    company: '',
                    description: '',
                };

                // Iterate through inputs to get data
                for (let j = 0; j < inputs.length; j++) {
                    speakerData[inputs[j].name as keyof SpeakerData] = inputs[j].value;
                }
                // Iterate through textareas to get data
                for (let j = 0; j < textareas.length; j++) {
                    const textarea: HTMLTextAreaElement = textareas[j];
                    speakerData[textarea.name as keyof SpeakerData] = textarea.value;
                }
                returnData.push(speakerData);
            }

            return returnData;
        },

        // K007: Adds speaker through ajax
        addNew: function (data: SpeakerData[]): void {
            const self = this;
            $.ajax({
                url: 'https://hayward.dev/admin/eventpagebuilder/speaker/addspeaker',
                type: 'POST',
                data: {items: data},
                showLoader: true,
                success: function (response: any) {
                    // Show save button
                    self.addItemsToOptions({
                        items: response.items,
                        itemIds: response.itemIds
                    });
                    self.toggleExpanded(false);
                },
                error: function (xhr: any, status: any, error: any) {
                    console.error(xhr.responseText);
                    alert(`Error occurred.`);
                }
            })
        },
        uploadImage: (data: any): void => {
            const input: any = document.getElementById('image-upload');
            const preview = document.getElementById('image-preview');

            if (input!.files && input!.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview!.innerHTML = '<img src="' + e.target!.result + '" alt="Uploaded Image">';
                };

                reader.readAsDataURL(input.files[0]);
            }
        },


        handleSubmit: function (data: HTMLElement): void {
            // Send data to backend and get response
            const speakerFormData: SpeakerData[] = this.getSpeakerFormData(data);
            this.addNew(speakerFormData);

        }
    });
});
