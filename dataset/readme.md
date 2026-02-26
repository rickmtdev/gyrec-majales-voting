# Majáles 2022 (Majáles naruby) voting data
`votes.csv` contains the voting table export, containing all submitted votes from the Majáles 2022 event.

`counts.csv` contains the summarized voting results.

`classes.csv` contains titles for each class ID (`class`).

## Data format
Description of `votes.csv` columns.
- `id`: Unique vote ID
- `class`: ID of selected class (1-12; PRI A, PRI B, ... SEX B)
- `date`: Datetime submitted (CEST)
- `agent`: Device agent of the voting device
- `ip`: IP of the voting device
- `vote_id`: First vote ID (not empty if the vote was submitted as a second choice by one voter)

Note: `id` starts at `2397` due to previous testing votes being removed.

## Vote collection method
See the use case in the parent readme.

## Redactions
Distinct `agent` and `ip` values were enumerated with `1..n` and all values were replaced with their respective enumerators.

These redactions can help in determining which votes were submitted by the same vote collector.