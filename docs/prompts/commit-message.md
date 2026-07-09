# Commit Message Prompt

Use this prompt when a commit message should be generated from the actual repository changes.

```text
Compose a commit message in English based only on the actual git diff.

Style:
- Professional, concise, and factual.
- Use neutral factual wording where natural: "added", "updated", "implemented", "fixed", "removed".
- Do not use marketing language.
- Do not mention changes that are not present in the diff.
- Prefer product/domain wording when it makes the change clearer, but keep it grounded in the diff.

Project context:
- This is a B2B SaaS return management application.
- The user interface is in German.
- Internal documentation may be in Russian.
- Commit messages must be in English.

Requirements:
1) Provide 3 commit title options:
   - short title, up to 72 characters;
   - medium descriptive title;
   - conventional commits style (`feat: ...`, `fix: ...`, `refactor: ...`, `docs: ...`).

2) Provide 1 final recommended commit message:
   - title + body.

3) Commit body:
   - 3-12 bullet points;
   - only key changes;
   - no more than one bullet per file or logical domain;
   - if there are more bullets than changed files/domains, merge related points;
   - bullets must be concise and factual;
   - prefer summarizing domains over listing every variable or line-level change;
   - use plain hyphen bullets;
   - target length: 1000-1500 characters for normal diffs;
   - hard maximum: 2500-3000 characters for large diffs.

4) If the diff mixes unrelated domains, mention that a split commit may be better.

5) If documentation and functional code changes are mixed, prefer a title that reflects the dominant change and mention documentation only if it materially changed.

6) Wrap the final recommended commit message in a code block for easy copying.

7) Before output, verify the body against requirement 3.

Use git to inspect the actual changes:
- `git status --short`
- `git diff --stat`
- `git diff`
- for staged commits, also check `git diff --cached`

Recommended commit types:
- `docs:` for roadmap, concept, README, legal notes, and prompt/documentation files.
- `feat:` for user-facing product functionality.
- `fix:` for bug fixes and broken behavior.
- `refactor:` for internal changes without intended behavior changes.
- `style:` only for formatting-only changes if the project accepts it.
- `chore:` for dependency, config, tooling, and housekeeping changes.
- Use `feat:` rather than `ui:` for user-facing interface improvements unless the repository already uses `ui:`.
```
