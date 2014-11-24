package ru.dreamkas.pos.remoteCommand;

import org.json.JSONException;
import org.json.JSONObject;

public class Request {
    private String mCommand;
    private String mTube = "android_autotestscommand";

    public Request(String mCommand) {
        this.mCommand = mCommand;
    }

    public Request(String mCommand, String mTube) {
        this.mCommand = mCommand;
        this.mTube = mTube;
    }

    public JSONObject toJson() throws JSONException {
        JSONObject request = new JSONObject();
        request.put("command", getCommand());
        request.put("replyTo", getTube());

        return request;
    }

    protected void setCommand(String value) {
        mCommand = value;
    }

    public void setTube(String value) {
        mTube = value;
    }

    public String getCommand() {
        return mCommand;
    }

    public String getTube() {
        return mTube;
    }
}

