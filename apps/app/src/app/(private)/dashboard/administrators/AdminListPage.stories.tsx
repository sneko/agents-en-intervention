import { Meta, StoryFn } from '@storybook/react';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { playFindMainTitle } from '@aei/docs/.storybook/testing';
import { AsAdmin as PrivateLayoutAsAdminStory } from '@aei/app/src/app/(private)/PrivateLayout.stories';
import { AdminListPage } from '@aei/app/src/app/(private)/dashboard/administrators/AdminListPage';
import { admins } from '@aei/app/src/fixtures/admin';
import { invitations } from '@aei/app/src/fixtures/invitation';
import { getTRPCMock } from '@aei/app/src/server/mock/trpc';

type ComponentType = typeof AdminListPage;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Pages/AdminList',
  component: AdminListPage,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'query',
        path: ['listAdmins'],
        response: {
          admins: [admins[0], admins[1], admins[2]],
        },
      }),
      getTRPCMock({
        type: 'query',
        path: ['listAdminInvitations'],
        response: {
          invitations: [invitations[0], invitations[1], invitations[2]],
        },
      }),
      getTRPCMock({
        type: 'mutation',
        path: ['revokeAdmin'],
        response: undefined,
      }),
    ],
  },
};

async function playFindTitle(canvasElement: HTMLElement): Promise<HTMLElement> {
  return await playFindMainTitle(canvasElement, /administrateurs/i);
}

const Template: StoryFn<ComponentType> = (args) => {
  return <AdminListPage {...args} />;
};

const NormalStory = Template.bind({});
NormalStory.args = {};
NormalStory.parameters = {
  ...defaultMswParameters,
};
NormalStory.play = async ({ canvasElement }) => {
  await playFindTitle(canvasElement);
};

export const Normal = prepareStory(NormalStory, {});

const WithLayoutStory = Template.bind({});
WithLayoutStory.args = {};
WithLayoutStory.parameters = {
  layout: 'fullscreen',
  ...defaultMswParameters,
};
WithLayoutStory.play = async ({ canvasElement }) => {
  await playFindTitle(canvasElement);
};

export const WithLayout = prepareStory(WithLayoutStory, {
  layoutStory: PrivateLayoutAsAdminStory,
});
