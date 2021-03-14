import React, { useState, useEffect } from 'react';
import ReactDOM from 'react-dom';
import api from '../api';
import { connection } from '../echo';
import { encrypt } from '../utils/crypto';
import { generateHash } from '../utils/strings';

const appKey = 'DzbSV9F9TPGzzomgszRhv1gITpwiusCtBtfH3ugDYpQ=';
const hashKey = 'hash';
const channel = 'poll';
const uuid = window.location.pathname.replace(/^\/|\/$/g, '');
const initialState = Object.freeze({
    pollName: '',
    answers: [],
    votes: []
});

const PollVoteForm = () => {
    const [hash, setHash] = useState(localStorage.getItem(hashKey))
    const [data, setData] = useState(initialState);
    const [name, setName] = useState('');
    const [answerId, setAnswerId] = useState(0);

    useEffect(() => {
        if (!hash) {
            const newHash = generateHash();

            localStorage.setItem(hashKey, newHash);
            setHash(newHash);
        }

        const echoConnection = connection();
        echoConnection
            .channel(channel)
            .listen(`.${channel}-${uuid}.voted`, voteData => {
                appendVote(voteData.vote);
            });

        const fetch = async () => {
            const [poll, votes] = await Promise.all([
                api.getPoll(uuid),
                api.getVotes(uuid),
            ]);

            const { name, answers } = poll.data.data;

            setData({
                pollName: name,
                answers,
                votes: votes.data.data,
            });
        };

        fetch();

        return () => {
            echoConnection.disconnect();
        };
    }, []);

    const appendVote = vote => setData(prevState => {
        if (prevState.votes.find(item => item.id === vote.id)) {
            return prevState;
        }

        return {
            ...prevState,
            votes: [...prevState.votes, vote],
        };
    });

    const handleChangeName = e => setName(e.currentTarget.value);
    const handleAnswerChange = e => setAnswerId(parseInt(e.currentTarget.value));
    const handleVote = async () => {
        try {
            const result = await api.vote(uuid, {
                hash: encrypt(appKey, hash),
                name,
                answer_id: answerId,
            });

            appendVote(result.data.data);
            setName('');
        } catch (e) {
            throw e;
        }
    };

    const renderResultsTable = () => (
        <table className="ex2-table">
            <thead>
            <tr>
                <th>Name</th>
                {data.answers.map(answer => (
                    <th key={answer.id}>{answer.name}</th>
                ))}
            </tr>
            </thead>
            <tbody>
            {data.votes.map(vote => (
                <tr key={vote.id}>
                    <td>{vote.name}</td>
                    {data.answers.map(answer => (
                        <td key={answer.id}>
                            {vote.answer.id === answer.id ? 'x' : ''}
                        </td>
                    ))}
                </tr>
            ))}
            </tbody>
        </table>
    );

    const canVote = answerId && name && !data.votes.find(vote => vote.hash === hash);

    return (
        <>
            <h1>
                {data.pollName}
            </h1>
            <div className="ex2-question">
                <div className="ex2-question__label">
                    Your name:
                </div>
                <div className="ex2-question__input">
                    <input
                        type="text"
                        className="input-text"
                        value={name}
                        onChange={handleChangeName}
                    />
                </div>
                <div className="ex2-question__answer">
                    {data.answers.map(answer => (
                        <label key={answer.id}>
                            <input
                                onChange={handleAnswerChange}
                                type="radio"
                                name="do-we-go"
                                value={answer.id}
                            />
                            {answer.name}
                        </label>
                    ))}
                </div>
                <div className="ex2-question__submit">
                    <input
                        disabled={!canVote}
                        onClick={handleVote}
                        type="submit"
                        className="btn"
                        value="Submit"
                    />
                </div>
            </div>
            <h1>Results</h1>
            <br/>
            {renderResultsTable()}
        </>
    );
}

if (document.getElementById('poll-vote-form')) {
    ReactDOM.render(<PollVoteForm/>, document.getElementById('poll-vote-form'));
}

export default PollVoteForm;
