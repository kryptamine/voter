import React, { useState } from 'react';
import ReactDOM from 'react-dom';
import api from '../api';

const PollCreateForm = () => {
    const [answers, setAnswers] = useState([]);
    const [name, setName] = useState('');

    const handleChangeName = e => setName(e.currentTarget.value);
    const handleAddOption = () => setAnswers(prevState => ([...prevState, '']));
    const handleRemoveOption = key => () => setAnswers(prevState => prevState.filter((_, i) => i !== key));
    const handleOptionChange = optionKey => e => {
        const value = e.currentTarget.value;

        setAnswers(prevState => prevState.map((option, key) => {
            if (key === optionKey) {
                return value;
            }

            return option;
        }));
    }

    const handleStart = async () => {
        const result = await api.createPoll( {
            name,
            answers
        });

        window.location.replace(`/${result.data.data.uuid}`);
    }

    return (
        <>
            <table className="poll-table">
                <thead>
                    <tr>
                        <th>Question:</th>
                        <th colSpan={2}>
                            <input
                                placeholder="Where do we go out tonight?"
                                type="text"
                                value={name}
                                className="input-text"
                                onChange={handleChangeName}
                            />
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {answers.map((option, key) => (
                        <tr key={key}>
                            <th>Answer {key + 1}:</th>
                            <td>
                                <input
                                    onChange={handleOptionChange(key)}
                                    type="text"
                                    placeholder="Please provide the option"
                                    value={option}
                                    className="input-text"
                                />
                            </td>
                            <td width="1%">
                                <button
                                    className="btn btn--minus"
                                    onClick={handleRemoveOption(key)}
                                >
                                    -
                                </button>
                            </td>
                        </tr>
                    ))}
                    {answers.length < 2 && (
                        <tr>
                            <td colSpan={3}>
                                * You should add at least two options to start
                            </td>
                        </tr>
                    )}
                    <tr>
                        <td colSpan={3} className="poll-table__plus">
                            <button
                                disabled={answers.length >= 100}
                                className="btn btn--plus"
                                onClick={handleAddOption}
                            >
                                +
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button
                onClick={handleStart}
                disabled={!name || answers.length < 2 || !answers.every(option => option)}
                className="btn btn--start"
            >
                Start
            </button>
        </>
    );
}

if (document.getElementById('poll-create-form')) {
    ReactDOM.render(<PollCreateForm/>, document.getElementById('poll-create-form'));
}

export default PollCreateForm;
