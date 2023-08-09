import { Meta, StoryFn } from '@storybook/react';

import { Features } from '@aei/app/src/app/(public)/(home)/Features';
import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';

type ComponentType = typeof Features;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Pages/Features',
  component: Features,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const Template: StoryFn<ComponentType> = (args) => {
  return <Features />;
};

const NormalStory = Template.bind({});
NormalStory.args = {};
NormalStory.parameters = {};

export const Normal = prepareStory(NormalStory);
