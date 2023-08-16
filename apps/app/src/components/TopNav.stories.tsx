import { Meta, StoryFn } from '@storybook/react';
import { within } from '@storybook/testing-library';

import { TopNav } from '@aei/app/src/components/TopNav';
import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';

type ComponentType = typeof TopNav;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Components/TopNav',
  component: TopNav,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

async function playFindUserItem(canvasElement: HTMLElement): Promise<HTMLElement> {
  return await within(canvasElement).findByText(/interventions/i);
}

const Template: StoryFn<ComponentType> = (args) => {
  return <TopNav {...args} />;
};

const NormalStory = Template.bind({});
NormalStory.args = {
  onNavOpen: () => {},
};
NormalStory.parameters = {};
NormalStory.play = async ({ canvasElement }) => {
  await playFindUserItem(canvasElement);
};

export const Normal = prepareStory(NormalStory);
