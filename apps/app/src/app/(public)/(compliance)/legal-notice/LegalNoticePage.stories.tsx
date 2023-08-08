import { Meta, StoryFn } from '@storybook/react';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { LegalNoticePage } from '@aei/app/src/app/(public)/(compliance)/legal-notice/LegalNoticePage';
import { AsVisitor as PublicLayoutAsVisitorStory } from '@aei/app/src/app/(public)/PublicLayout.stories';

type ComponentType = typeof LegalNoticePage;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Pages/LegalNotice',
  component: LegalNoticePage,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const Template: StoryFn<ComponentType> = (args) => {
  return <LegalNoticePage />;
};

const NormalStory = Template.bind({});
NormalStory.args = {};
NormalStory.parameters = {};

export const Normal = prepareStory(NormalStory);

const WithLayoutStory = Template.bind({});
WithLayoutStory.args = {};
WithLayoutStory.parameters = {
  layout: 'fullscreen',
};

export const WithLayout = prepareStory(WithLayoutStory, {
  layoutStory: PublicLayoutAsVisitorStory,
});
