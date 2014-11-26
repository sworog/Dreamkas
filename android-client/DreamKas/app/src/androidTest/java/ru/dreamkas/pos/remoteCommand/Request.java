package ru.dreamkas.pos.remoteCommand;

import org.json.JSONException;
import org.json.JSONObject;

public class Request {
    private String mCommand;
    private String mTubeId = "android_autotestscommand";

    public Request(String mCommand) {
        this.mCommand = mCommand;
    }

    public Request(String mCommand, String mTube) {
        this.mCommand = mCommand;
        this.mTubeId = mTube;
    }

    public JSONObject toJson() throws JSONException {
        JSONObject request = new JSONObject();
        request.put("command", getCommand());
        request.put("replyTo", getTubeId());

        return request;
    }

    protected void setCommand(String value) {
        mCommand = value;
    }

    public void setTubeId(String value) {
        mTubeId = value;
    }

    public String getCommand() {
        return mCommand;
    }

    public String getTubeId() {
        return mTubeId;
    }
}

