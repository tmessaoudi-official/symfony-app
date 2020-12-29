import * as fileSystem from 'fs';
import * as childProcess from 'child_process';
import * as colors from 'colors';

interface Config {
    root: {
        allowed: Array<String>;
        disallowed: Array<String>;
    };
    flow: {
        separator: String | `/`;
        prefix: Array<String>;
        suffix: {
            regex: String;
        };
        range: Array<Number>;
    };
    skipMerge: boolean | true;
}

class GitBranching {
    protected configPath: String = String(
        process.env.GIT_BRANCHING_JSON || `./.git-branching.json`,
    );
    protected config: Config = this.getConfig();
    protected unknownError = true;
    protected complianceNotice: boolean = false;

    constructor() {
        colors.enable();
    }

    protected getConfig(): Config {
        return JSON.parse(
            // @ts-ignore
            fileSystem.readFileSync(this.configPath, `utf-8`),
        );
    }

    protected gitExec(args: Array<Object>): String {
        return (
            childProcess
                // @ts-ignore
                .execFileSync(...args)
                .toString()
                .trim()
        );
    }

    protected getGitBranchName(): String {
        return this.gitExec([`git`, [`rev-parse`, `--abbrev-ref`, `HEAD`]]);
    }

    protected getGitLastCommitMessage(): String {
        return this.gitExec([`git`, [`log`, `-1`]]);
    }

    protected getGitLastMergeCommitMessage(): String {
        return this.gitExec([`git`, [`log`, `--merges`, `-n`, `1`]]);
    }

    protected shouldSkip(): boolean {
        return (
            (this.config.skipMerge &&
                this.getGitLastMergeCommitMessage() ===
                    this.getGitLastCommitMessage()) ||
            this.config.root.allowed.includes(this.getGitBranchName())
        );
    }

    protected isDisallowedToPushToBranch(): void {
        if (
            this.config.root.disallowed.includes(this.getGitBranchName()) ||
            this.getGitBranchName().indexOf(
                String(this.config.flow.separator),
            ) === -1
        ) {
            this.unknownError = false;
            console.error(
                `You are not allowed to push to '${this.getGitBranchName()}' !! use gitflow please`
                    .bgRed.white,
            );
            if (this.config.root.allowed.length > 0) {
                console.error(
                    `If you want to push to a root branch here are the authorized ones [${this.config.root.allowed.join(
                        `, `,
                    )}]`.bgRed.white,
                );
            }
        }
    }

    protected hasUnknownCombination(): void {
        if (
            this.getGitBranchName().indexOf(
                String(this.config.flow.separator),
            ) !== -1
        ) {
            this.unknownError = false;
            if (
                !this.config.flow.prefix.includes(
                    this.getGitBranchName().split(
                        String(this.config.flow.separator),
                    )[0],
                )
            ) {
                console.error(
                    `Branch name '${this.getGitBranchName()}' has an unknown prefix ${
                        this.getGitBranchName().split(
                            String(this.config.flow.separator),
                        )[0]
                    }`.bgRed.white,
                );
                console.error(
                    `Here are the supported prefixes [${this.config.flow.prefix.join(
                        `, `,
                    )}]`.bgRed.white,
                );
            }
            if (
                this.getGitBranchName()
                    .split(String(this.config.flow.separator))[1]
                    .match('^[^A-Za-z].*')
            ) {
                console.error(
                    `Branch suffix '${this.getGitBranchName()}' should start with a letter`
                        .bgRed.white,
                );
            }
        }
    }

    protected branchNameExceedsRange(): void {
        if (
            this.getGitBranchName().length > this.config.flow.range[1] &&
            !this.shouldSkip()
        ) {
            this.unknownError = false;
            console.error(
                `Branch name '${this.getGitBranchName()}' exceeds the maximums characters allowed '${
                    this.config.flow.range[1]
                }' by '${
                    this.getGitBranchName().length -
                    Number(this.config.flow.range[1])
                }'`.bgRed.white,
            );
        }
    }

    protected branchNameLessThanRange(): void {
        if (
            this.getGitBranchName().length < this.config.flow.range[0] &&
            !this.shouldSkip()
        ) {
            this.unknownError = false;
            console.error(
                `Branch name '${this.getGitBranchName()}' cannot contain less than '${
                    this.config.flow.range[0]
                }' characters, '${this.getGitBranchName().length}' given`.bgRed
                    .white,
            );
        }
    }

    public run(): void {
        if (this.shouldSkip()) {
            console.log(
                `Ignoring git branching script, it seems like you are merging something or allowed to push to ${this.getGitBranchName()} :)`
                    .bgGreen.white,
            );
            process.exit(0);
        }
        const regExp = new RegExp(
            `^${
                this.config.root.allowed.length > 0
                    ? `(${this.config.root.allowed.join(`|`)})|`
                    : ``
            }((${this.config.flow.prefix.join(`|`)})${
                this.config.flow.separator || `/`
            }${this.config.flow.suffix.regex})$`,
        );
        if (
            !regExp.test(String(this.getGitBranchName())) &&
            !this.shouldSkip()
        ) {
            this.isDisallowedToPushToBranch();
            this.hasUnknownCombination();
            this.complianceNotice = true;
        }

        this.branchNameExceedsRange();
        this.branchNameLessThanRange();

        if (this.complianceNotice) {
            console.error(
                `Branch name '${this.getGitBranchName()}' does not comply with our standards!! please use gitflow`
                    .bgRed.white,
            );
            console.error(
                `Branch name should follow this pattern [${this.config.flow.prefix.join(
                    `|`,
                )}]/${this.config.flow.suffix.regex}`.bgRed.white,
            );
            process.exit(1);
        }
        process.exit(0);
    }
}

let gitBranching = new GitBranching();
gitBranching.run();
