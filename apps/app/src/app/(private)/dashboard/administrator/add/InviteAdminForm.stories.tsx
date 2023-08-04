import { Meta, StoryFn } from '@storybook/react';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { playFindForm } from '@aei/docs/.storybook/testing';
import { InviteAdminForm } from '@aei/app/src/app/(private)/dashboard/administrator/add/InviteAdminForm';
import { InviteAdminPrefillSchema } from '@aei/app/src/models/actions/admin';
import { getTRPCMock } from '@aei/app/src/server/mock/trpc';

type ComponentType = typeof InviteAdminForm;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Forms/InviteAdmin',
  component: InviteAdminForm,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'mutation',
        path: ['inviteAdmin'],
        response: undefined,
      }),
    ],
  },
};

const Template: StoryFn<ComponentType> = (args) => {
  return <InviteAdminForm {...args} />;
};

const EmptyStory = Template.bind({});
EmptyStory.args = {};
EmptyStory.parameters = { ...defaultMswParameters };
EmptyStory.play = async ({ canvasElement }) => {
  await playFindForm(canvasElement);
};

export const Empty = prepareStory(EmptyStory);

const FilledStory = Template.bind({});
FilledStory.args = {
  prefill: InviteAdminPrefillSchema.parse({
    inviteeEmail: 'jean@france.fr',
    inviteeFirstname: 'Jean',
    inviteeLastname: 'Derrien',
  }),
};
FilledStory.parameters = { ...defaultMswParameters };
FilledStory.play = async ({ canvasElement }) => {
  await playFindForm(canvasElement);
};

export const Filled = prepareStory(FilledStory);
