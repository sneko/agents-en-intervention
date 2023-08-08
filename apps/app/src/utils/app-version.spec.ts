import {
  getFallbackCommitInformation,
  getFallbackCommitSha,
  getFallbackCommitTag,
  getGitRevisionDate,
  getHumanVersion,
  getRepositoryInformation,
  getTechnicalVersion,
} from '@aei/app/src/utils/app-version';

describe('app-version', () => {
  // Note: we skip following tests since they reach GitHub API that has a quota limit
  // and since in their CI the IP is shared with everyone... :)

  describe.skip('getRepositoryInformation()', () => {
    it('should return correct information', () => {
      const repoInfo = getRepositoryInformation();

      expect(repoInfo.href).toStrictEqual('git+https://github.com/sneko/agents-en-intervention.git');
    });
  });

  describe.skip('as in Scalingo environment', () => {
    // Simulate the commit SHA Scalingo provides
    process.env.SOURCE_VERSION = 'bedf63af57c32f3750e2d0f466066fcf549960f8';

    describe('getFallbackCommitSha()', () => {
      it('should return the environment variable value', async () => {
        const commitSha = await getFallbackCommitSha();

        expect(commitSha).toStrictEqual(process.env.SOURCE_VERSION);
      });
    });

    describe('getFallbackCommitInformation()', () => {
      it('should get commit from GitHub', async () => {
        const commitInfo = await getFallbackCommitInformation();

        expect(commitInfo.sha).toStrictEqual('bedf63af57c32f3750e2d0f466066fcf549960f8');
        expect(commitInfo.commit.author).not.toBeNull();
        expect(commitInfo.commit.author?.date).toStrictEqual('2022-11-21T16:17:32Z');
      });
    });

    // describe('getGitRevisionDate()', () => {
    //   it('should get tag from GitHub', async () => {
    //     const tag = await getGitRevisionDate();

    //     expect(tag).toStrictEqual('xxxxxxxxx');
    //   });
    // });

    // describe('getHumanVersion()', () => {
    //   it('should get tag from GitHub', async () => {
    //     const tag = await getTechnicalVersion();

    //     expect(tag).toStrictEqual('xxxxxxxxx');
    //   });
    // });

    // describe('getTechnicalVersion()', () => {
    //   it('should get tag from GitHub', async () => {
    //     const tag = await getTechnicalVersion();

    //     expect(tag).toStrictEqual('xxxxxxxxx');
    //   });
    // });
  });
});
