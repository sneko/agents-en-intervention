import { Meta, StoryFn } from '@storybook/react';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { playFindForm } from '@aei/docs/.storybook/testing';
import { UpdateNoteForm } from '@aei/app/src/app/(private)/dashboard/authority/[authorityId]/case/[caseId]/UpdateNoteForm';
import { notes } from '@aei/app/src/fixtures/case';
import { getTRPCMock } from '@aei/app/src/server/mock/trpc';

type ComponentType = typeof UpdateNoteForm;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Forms/UpdateNote',
  component: UpdateNoteForm,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'mutation',
        path: ['updateCaseNote'],
        response: {
          note: notes[0],
        },
      }),
    ],
  },
};

const Template: StoryFn<ComponentType> = (args) => {
  return <UpdateNoteForm {...args} />;
};

const NormalStory = Template.bind({});
NormalStory.args = {
  note: notes[0],
};
NormalStory.parameters = { ...defaultMswParameters };
NormalStory.play = async ({ canvasElement }) => {
  await playFindForm(canvasElement);
};

export const Normal = prepareStory(NormalStory);
