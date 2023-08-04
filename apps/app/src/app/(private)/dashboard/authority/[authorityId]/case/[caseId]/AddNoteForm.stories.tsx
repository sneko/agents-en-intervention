import { Meta, StoryFn } from '@storybook/react';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { playFindForm } from '@aei/docs/.storybook/testing';
import { AddNoteForm } from '@aei/app/src/app/(private)/dashboard/authority/[authorityId]/case/[caseId]/AddNoteForm';
import { notes } from '@aei/app/src/fixtures/case';
import { AddNoteToCasePrefillSchema } from '@aei/app/src/models/actions/case';
import { getTRPCMock } from '@aei/app/src/server/mock/trpc';
import sampleHello from '@aei/ui/src/Editor/sample-hello.lexical';

type ComponentType = typeof AddNoteForm;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Forms/AddNote',
  component: AddNoteForm,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'mutation',
        path: ['addNoteToCase'],
        response: {
          note: notes[0],
        },
      }),
    ],
  },
};

const Template: StoryFn<ComponentType> = (args) => {
  return <AddNoteForm {...args} />;
};

const EmptyStory = Template.bind({});
EmptyStory.args = {
  prefill: {
    caseId: '00000000-0000-0000-0000-000000000000',
    date: new Date('December 15, 2022 03:24:00 UTC'), // Fix the date to avoid UI change during snapshots
  },
};
EmptyStory.parameters = { ...defaultMswParameters };
EmptyStory.play = async ({ canvasElement }) => {
  await playFindForm(canvasElement);
};

export const Empty = prepareStory(EmptyStory);

const FilledStory = Template.bind({});
FilledStory.args = {
  prefill: AddNoteToCasePrefillSchema.parse({
    caseId: '00000000-0000-0000-0000-000000000000',
    date: new Date('December 15, 2022 03:24:00 UTC'),
    content: sampleHello,
  }),
};
FilledStory.parameters = { ...defaultMswParameters };
FilledStory.play = async ({ canvasElement }) => {
  await playFindForm(canvasElement);
};

export const Filled = prepareStory(FilledStory);
