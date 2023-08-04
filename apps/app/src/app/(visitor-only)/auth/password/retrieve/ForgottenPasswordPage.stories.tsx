import { Meta, StoryFn } from '@storybook/react';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { playFindForm, playFindFormInMain } from '@aei/docs/.storybook/testing';
import { Normal as VisitorOnlyLayoutNormalStory } from '@aei/app/src/app/(visitor-only)/VisitorOnlyLayout.stories';
import {
  ForgottenPasswordPage,
  ForgottenPasswordPageContext,
} from '@aei/app/src/app/(visitor-only)/auth/password/retrieve/ForgottenPasswordPage';
import { Empty as RetrievePasswordFormEmptyStory } from '@aei/app/src/app/(visitor-only)/auth/password/retrieve/RetrievePasswordForm.stories';

type ComponentType = typeof ForgottenPasswordPage;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Pages/ForgottenPassword',
  component: ForgottenPasswordPage,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const Template: StoryFn<ComponentType> = (args) => {
  return <ForgottenPasswordPage />;
};

const NormalStory = Template.bind({});
NormalStory.args = {};
NormalStory.play = async ({ canvasElement }) => {
  await playFindForm(canvasElement);
};

export const Normal = prepareStory(NormalStory, {
  childrenContext: {
    context: ForgottenPasswordPageContext,
    value: {
      ContextualRetrievePasswordForm: RetrievePasswordFormEmptyStory,
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
    context: ForgottenPasswordPageContext,
    value: {
      ContextualRetrievePasswordForm: RetrievePasswordFormEmptyStory,
    },
  },
});
