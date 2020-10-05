#!/usr/bin/env python

"""
List branches, which are not in any remote.
They may be new, or someone might deleted them from the remote accidentaly.
"""

import os

branches = {
    line[2:].strip()
    for line in os.popen('git branch -a')
    if ' -> ' not in line
}

remotes = {
    '/'.join(branch.split('/')[2:])
    for branch in branches
    if branch.startswith('remotes/')
}

local_branches = set(filter(lambda x: not x.startswith('remotes/'), branches))

print('\n'.join('git branch -d {}'.format(branch) for branch in (local_branches - remotes)))