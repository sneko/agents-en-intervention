import HomeIcon from '@mui/icons-material/Home';
import { Meta, StoryFn } from '@storybook/react';
import { within } from '@storybook/testing-library';

import { SideNavItem } from '@aei/app/src/components/SideNavItem';
import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';

type ComponentType = typeof SideNavItem;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Components/SideNavItem',
  component: SideNavItem,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

async function playFindItem(canvasElement: HTMLElement): Promise<HTMLElement> {
  return await within(canvasElement).findByText(/my test/i);
}

const Template: StoryFn<ComponentType> = (args) => {
  return <SideNavItem {...args} />;
};

const NormalStory = Template.bind({});
NormalStory.args = {
  title: 'My test',
  path: '',
  active: false,
  disabled: false,
  external: false,
  icon: <HomeIcon fontSize="small" />,
};
NormalStory.parameters = {};
NormalStory.play = async ({ canvasElement }) => {
  await playFindItem(canvasElement);
};

export const Normal = prepareStory(NormalStory);
