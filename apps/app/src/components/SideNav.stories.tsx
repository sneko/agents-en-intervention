import { Meta, StoryFn } from '@storybook/react';
import { within } from '@storybook/testing-library';

import { SideNav } from '@aei/app/src/components/SideNav';
import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';

type ComponentType = typeof SideNav;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Components/SideNav',
  component: SideNav,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const Template: StoryFn<ComponentType> = (args) => {
  return <SideNav {...args} />;
};

const NormalStory = Template.bind({});
NormalStory.args = {
  open: true,
  onClose: () => {},
};
NormalStory.parameters = {};
NormalStory.play = async ({ canvasElement }) => {
  await within(canvasElement).findByText(/accueil/i);
};

export const Normal = prepareStory(NormalStory);
