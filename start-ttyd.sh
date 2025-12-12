#!/bin/bash

# FluxSSH ttyd starter script
# Usage: start-ttyd.sh <port> <theme_json> <command...>

# Set PATH to include common binary locations
export PATH="/usr/local/bin:/opt/homebrew/bin:/usr/bin:/bin:/usr/sbin:/sbin:$PATH"

PORT=${1:-7700}
shift
THEME_JSON=${1:-'{}'}
shift

# Kill any existing ttyd on this port using full path
/usr/sbin/lsof -ti:$PORT 2>/dev/null | /usr/bin/xargs kill -9 2>/dev/null
/bin/sleep 0.2

# Get ttyd path
TTYD_PATH=$(/usr/bin/which ttyd 2>/dev/null)
if [ -z "$TTYD_PATH" ]; then
    TTYD_PATH="/opt/homebrew/bin/ttyd"
fi

if [ ! -x "$TTYD_PATH" ]; then
    echo "ERROR:ttyd not found"
    exit 1
fi

# Start ttyd completely detached using nohup and disown
# On macOS, we use nohup with output redirection and & to background
if [ -n "$1" ]; then
    # SSH command provided - pass theme if available
    if [ "$THEME_JSON" != "{}" ]; then
        /usr/bin/nohup "$TTYD_PATH" --port "$PORT" --writable --client-option "theme=$THEME_JSON" "$@" </dev/null >/dev/null 2>&1 &
    else
        /usr/bin/nohup "$TTYD_PATH" --port "$PORT" --writable "$@" </dev/null >/dev/null 2>&1 &
    fi
    disown 2>/dev/null
else
    # Default to bash
    if [ "$THEME_JSON" != "{}" ]; then
        /usr/bin/nohup "$TTYD_PATH" --port "$PORT" --writable --client-option "theme=$THEME_JSON" /bin/bash </dev/null >/dev/null 2>&1 &
    else
        /usr/bin/nohup "$TTYD_PATH" --port "$PORT" --writable /bin/bash </dev/null >/dev/null 2>&1 &
    fi
    disown 2>/dev/null
fi

# Wait for ttyd to start and get PID
/bin/sleep 1
PID=$(/usr/sbin/lsof -ti:$PORT 2>/dev/null | /usr/bin/head -1)

if [ -n "$PID" ]; then
    echo "SUCCESS:$PID"
    exit 0
else
    echo "ERROR:Failed to start ttyd on port $PORT"
    exit 1
fi
