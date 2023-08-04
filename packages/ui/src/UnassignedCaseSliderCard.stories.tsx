import { Meta, StoryFn } from '@storybook/react';
import { within } from '@storybook/testing-library';
import addHours from 'date-fns/addHours';
import { mockDateDecorator } from 'storybook-mock-date-decorator';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { uiAttachments } from '@aei/app/src/fixtures/attachment';
import { cases } from '@aei/app/src/fixtures/case';
import { citizens } from '@aei/app/src/fixtures/citizen';
import { CaseSchema } from '@aei/app/src/models/entities/case';
import { UnassignedCaseSliderCard } from '@aei/ui/src/UnassignedCaseSliderCard';

type ComponentType = typeof UnassignedCaseSliderCard;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Components/UnassignedCaseSliderCard',
  component: UnassignedCaseSliderCard,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

async function playFindElement(canvasElement: HTMLElement): Promise<HTMLElement> {
  return await within(canvasElement).findByText(/avancement du dossier/i);
}

const Template: StoryFn<ComponentType> = (args) => {
  return <UnassignedCaseSliderCard {...args} />;
};

const NormalStory = Template.bind({});
NormalStory.args = {
  caseLink: '',
  case: cases[0],
  citizen: citizens[0],
  attachments: uiAttachments,
  assignAction: async () => {},
};
NormalStory.play = async ({ canvasElement }) => {
  await playFindElement(canvasElement);
};

export const Normal = prepareStory(NormalStory);

const dateMock = new Date('December 15, 2022 03:24:00 UTC');
const ReminderSoonStory = Template.bind({});
ReminderSoonStory.args = {
  caseLink: '',
  case: CaseSchema.parse({ ...cases[0], termReminderAt: addHours(dateMock, 3) }),
  citizen: citizens[0],
  attachments: uiAttachments,
  assignAction: async () => {},
};
ReminderSoonStory.parameters = {
  date: dateMock, // Mock date generation so underlying `isReminderSoon()` returns "true"
};
ReminderSoonStory.decorators = [mockDateDecorator];
ReminderSoonStory.play = async ({ canvasElement }) => {
  await playFindElement(canvasElement);
};

export const ReminderSoon = prepareStory(ReminderSoonStory);
