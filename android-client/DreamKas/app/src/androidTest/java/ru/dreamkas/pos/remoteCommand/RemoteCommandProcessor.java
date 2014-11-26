package ru.dreamkas.pos.remoteCommand;

import android.util.Pair;

import com.surftools.BeanstalkClient.Client;
import com.surftools.BeanstalkClient.Job;
import com.surftools.BeanstalkClientImpl.ClientImpl;

import org.apache.commons.lang3.StringUtils;
import org.json.JSONException;

import java.util.ArrayList;
import java.util.UUID;

public class RemoteCommandProcessor {
    private String mServiceHost;
    private String mServerHost;
    private Integer mServicePort;
    private String mEnv;

    public RemoteCommandProcessor (String serviceHost, Integer servicePort, String env, String serverHost){
        mServiceHost = serviceHost;
        mServicePort = servicePort;
        mServerHost = serverHost;
        mEnv = env;
    }

    private String getCommandTubeName(){
        return getTubePrefix() + "command";
    }

    private String getTubePrefix(){
        return String.format("%s_au_%s", mServerHost, mEnv);
    }

    public Pair<Status, String> processCommand(Request request) {

        Client client = new ClientImpl(mServiceHost, mServicePort);
        String replyTubeId = UUID.randomUUID().toString();

        request.setTube(replyTubeId);

        ArrayList<String> log = new ArrayList<String>();
        log.add(String.format("Command %s send to tube %s, listen from tube %s", request.getCommand(), request.getTube(), replyTubeId));

        client.useTube(getCommandTubeName());

        long jobId = 0;
        try{
            jobId = client.put(65335, 0, 120, request.toJson().toString().getBytes());
            log.add("Client.put() return jobId: " + jobId);
        }catch (JSONException ex){
            return new Pair<Status, String>(Status.UNKNOWN, "Bad request. " + ex.getMessage());
        }

        client.watch(getTubePrefix()+replyTubeId);

        Job job;
        final long startTime = System.currentTimeMillis();
        final long endTime = startTime + 10000;

        Status result = Status.UNKNOWN;
        do {
            job = client.reserve(1);

            if(job == null){
                log.add("Reserved job is null.");
                continue;
            }

            Response response = null;
            try{
                response = new Response(job);
            }catch (JSONException ex){
                log.add("Couldn't build response object: " + ex.getMessage());
            }

            if(response != null){
                if(!log.get(log.size() - 1).equals(response.getData())){
                    log.add(String.format("status: %d; data: %s",response.getStatus().value(), response.getData()));
                }

                client.delete(job.getJobId());

                if(response.getStatus().equals(Status.FAILED) || response.getStatus().equals(Status.SUCCESS)){
                    result = response.getStatus();
                    break;
                }
            }
        }while (System.currentTimeMillis() < endTime);

        client.close();

        return new Pair<Status, String>( result, StringUtils.join(log, "\r\n"));
    }
}
