import { Meta, StoryFn } from '@storybook/react';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { playFindForm } from '@aei/docs/.storybook/testing';
import { InviteAgentForm } from '@aei/app/src/app/(private)/dashboard/authority/[authorityId]/agent/add/InviteAgentForm';
import { InviteAgentPrefillSchema } from '@aei/app/src/models/actions/agent';
import { getTRPCMock } from '@aei/app/src/server/mock/trpc';

type ComponentType = typeof InviteAgentForm;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Forms/InviteAgent',
  component: InviteAgentForm,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'mutation',
        path: ['inviteAgent'],
        response: undefined,
      }),
    ],
  },
};

const Template: StoryFn<ComponentType> = (args) => {
  return <InviteAgentForm {...args} />;
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
  prefill: InviteAgentPrefillSchema.parse({
    authorityId: '00000000-0000-0000-0000-000000000000',
    inviteeEmail: 'jean@france.fr',
    inviteeFirstname: 'Jean',
    inviteeLastname: 'Derrien',
    grantMainAgent: true,
  }),
};
FilledStory.parameters = { ...defaultMswParameters };
FilledStory.play = async ({ canvasElement }) => {
  await playFindForm(canvasElement);
};

export const Filled = prepareStory(FilledStory);
