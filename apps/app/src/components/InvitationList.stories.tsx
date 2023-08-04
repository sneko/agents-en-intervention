import { Meta, StoryFn } from '@storybook/react';
import { within } from '@storybook/testing-library';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { InvitationList } from '@aei/app/src/components/InvitationList';
import { invitations } from '@aei/app/src/fixtures/invitation';
import { getTRPCMock } from '@aei/app/src/server/mock/trpc';

type ComponentType = typeof InvitationList;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Components/InvitationList',
  component: InvitationList,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'mutation',
        path: ['cancelInvitation'],
        response: undefined,
      }),
    ],
  },
};

const Template: StoryFn<ComponentType> = (args) => {
  return <InvitationList {...args} />;
};

const NormalStory = Template.bind({});
NormalStory.args = {
  invitations: invitations,
};
NormalStory.parameters = {
  ...defaultMswParameters,
};
NormalStory.play = async ({ canvasElement }) => {
  await within(canvasElement).findByRole('grid', {
    name: /liste/i,
  });
};

export const Normal = prepareStory(NormalStory);
