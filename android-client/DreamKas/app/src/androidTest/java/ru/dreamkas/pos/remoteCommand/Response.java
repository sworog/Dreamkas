package ru.dreamkas.pos.remoteCommand;


import com.surftools.BeanstalkClient.Job;

import org.json.JSONException;
import org.json.JSONObject;

public class Response {
    private String mData;
    private Status mStatus;

    public Response(Job job) throws JSONException {
        this(job.getData());
    }

    public Response(byte[] source) throws JSONException {
        JSONObject response = new JSONObject(new String(source));
        setStatus(Status.fromValue(response.getInt("status")));
        setData(response.getString("data"));
    }

    private void setData(String mData) {
        this.mData = mData;
    }

    private void setStatus(Status mStatus) {
        this.mStatus = mStatus;
    }

    public String getData() {

        return mData;
    }

    public Status getStatus() {
        return mStatus;
    }


}
