import { Meta, StoryFn } from '@storybook/react';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { playFindForm, playFindFormInMain } from '@aei/docs/.storybook/testing';
import { AddAdminPage, AddAdminPageContext } from '@aei/app/src/app/(private)/dashboard/administrator/add/AddAdminPage';
import { Empty as InviteAdminFormEmptyStory } from '@aei/app/src/app/(private)/dashboard/administrator/add/InviteAdminForm.stories';
import { Normal as VisitorOnlyLayoutNormalStory } from '@aei/app/src/app/(visitor-only)/VisitorOnlyLayout.stories';

type ComponentType = typeof AddAdminPage;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Pages/AddAdmin',
  component: AddAdminPage,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const Template: StoryFn<ComponentType> = (args) => {
  return <AddAdminPage {...args} />;
};

const NormalStory = Template.bind({});
NormalStory.args = {};
NormalStory.parameters = {};
NormalStory.play = async ({ canvasElement }) => {
  await playFindForm(canvasElement);
};

export const Normal = prepareStory(NormalStory, {
  childrenContext: {
    context: AddAdminPageContext,
    value: {
      ContextualInviteAdminForm: InviteAdminFormEmptyStory,
    },
  },
});

const WithLayoutStory = Template.bind({});
WithLayoutStory.args = {};
WithLayoutStory.parameters = {
  layout: 'fullscreen',
};
WithLayoutStory.play = async ({ canvasElement }) => {
  await playFindFormInMain(canvasElement);
};

export const WithLayout = prepareStory(WithLayoutStory, {
  layoutStory: VisitorOnlyLayoutNormalStory,
  childrenContext: {
    context: AddAdminPageContext,
    value: {
      ContextualInviteAdminForm: InviteAdminFormEmptyStory,
    },
  },
});
