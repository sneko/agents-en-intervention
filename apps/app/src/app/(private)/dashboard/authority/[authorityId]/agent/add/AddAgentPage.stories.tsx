import { Meta, StoryFn } from '@storybook/react';

import { ComponentProps, StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { playFindAlert, playFindForm, playFindFormInMain } from '@aei/docs/.storybook/testing';
import { AddAgentPage, AddAgentPageContext } from '@aei/app/src/app/(private)/dashboard/authority/[authorityId]/agent/add/AddAgentPage';
import { Empty as InviteAgentFormEmptyStory } from '@aei/app/src/app/(private)/dashboard/authority/[authorityId]/agent/add/InviteAgentForm.stories';
import { Normal as VisitorOnlyLayoutNormalStory } from '@aei/app/src/app/(visitor-only)/VisitorOnlyLayout.stories';
import { authorities } from '@aei/app/src/fixtures/authority';
import { AuthoritySchema } from '@aei/app/src/models/entities/authority';
import { getTRPCMock } from '@aei/app/src/server/mock/trpc';

type ComponentType = typeof AddAgentPage;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Pages/AddAgent',
  component: AddAgentPage,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'query',
        path: ['getAuthority'],
        response: {
          authority: AuthoritySchema.parse(authorities[0]),
        },
      }),
    ],
  },
};

const commonComponentProps: ComponentProps<ComponentType> = {
  params: {
    authorityId: 'b79cb3ba-745e-5d9a-8903-4a02327a7e01',
  },
};

const Template: StoryFn<ComponentType> = (args) => {
  return <AddAgentPage {...args} />;
};

const NormalStory = Template.bind({});
NormalStory.args = {
  ...commonComponentProps,
};
NormalStory.parameters = { ...defaultMswParameters };
NormalStory.play = async ({ canvasElement }) => {
  await playFindForm(canvasElement);
};

export const Normal = prepareStory(NormalStory, {
  childrenContext: {
    context: AddAgentPageContext,
    value: {
      ContextualInviteAgentForm: InviteAgentFormEmptyStory,
    },
  },
});

const NotFoundStory = Template.bind({});
NotFoundStory.args = {
  ...commonComponentProps,
};
NotFoundStory.parameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'query',
        path: ['getAuthority'],
        response: null as any, // TODO: manage the case when an error of "not found" should be sent
      }),
    ],
  },
};
NotFoundStory.play = async ({ canvasElement }) => {
  await playFindAlert(canvasElement);
};

export const NotFound = prepareStory(NotFoundStory, {
  childrenContext: {
    context: AddAgentPageContext,
    value: {
      ContextualInviteAgentForm: InviteAgentFormEmptyStory,
    },
  },
});

const WithLayoutStory = Template.bind({});
WithLayoutStory.args = {
  ...commonComponentProps,
};
WithLayoutStory.parameters = {
  layout: 'fullscreen',
  ...defaultMswParameters,
};
WithLayoutStory.play = async ({ canvasElement }) => {
  await playFindFormInMain(canvasElement);
};

export const WithLayout = prepareStory(WithLayoutStory, {
  layoutStory: VisitorOnlyLayoutNormalStory,
  childrenContext: {
    context: AddAgentPageContext,
    value: {
      ContextualInviteAgentForm: InviteAgentFormEmptyStory,
    },
  },
});
